<?php

namespace Bavix\FlowNative;

use Bavix\Exceptions\NotFound;
use Bavix\Helpers\File;

class FlowNative
{

    /**
     * @var Context
     */
    protected $content;

    /**
     * @var Sandbox
     */
    protected $sandbox;

    /**
     * @var array
     */
    protected $folders = [];

    /**
     * FlowNative constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context = null)
    {
        if ($context)
        {
            $this->setContent($context);
        }
    }

    protected function setContent(Context $context)
    {
        $this->content = $context;
        $this->content->setFlow($this);
    }

    /**
     * Content for Sandbox
     *
     * @return Context
     */
    public function content(): Context
    {
        if (!$this->content)
        {
            $this->setContent(new Context(
                new Helper(),
                new Extensions()
            ));
        }

        return $this->content;
    }

    /**
     * @param string $name
     * @param string $path
     *
     * @return $this
     */
    public function addFolder($name, $path): self
    {
        $this->folders[$name] = $path;

        return $this;
    }

    /**
     * @param string $name
     * @param string $path
     *
     * @return string
     */
    protected function folder($name, $path): string
    {
        $fullPath = realpath($this->folders[$name] . '/' . $path);

        if (!$fullPath)
        {
            throw new NotFound\Path('Path `' . $name . ':' . $path . '` not found!');
        }

        return $fullPath;
    }

    /**
     * @param string $view
     *
     * @return string
     */
    public function path(string $view): string
    {
        if (File::exists($view))
        {
            return $view;
        }

        list($bundle, $path) = explode(':', $view, 2);

        return $this->folder($bundle, $path);
    }

    /**
     * @return Sandbox
     */
    public function sandbox(): Sandbox
    {
        if (!$this->sandbox)
        {
            $this->sandbox = new Sandbox($this->content());
        }

        return $this->sandbox;
    }

    /**
     * @param string $view
     * @param array  $arguments
     *
     * @return mixed
     */
    public function render($view, array $arguments = [])
    {
        \ob_start();

        $this->sandbox()
            ->execute($view, $arguments);

        return \trim(\ob_get_clean());
    }

}
