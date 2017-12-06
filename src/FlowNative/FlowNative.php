<?php

namespace Bavix\FlowNative;

use Bavix\Exceptions\NotFound;

class FlowNative
{

    /**
     * @var Extensions
     */
    protected $extensions;

    /**
     * @var Context
     */
    protected $content;

    /**
     * @var array
     */
    protected $folders = [];

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var int
     */
    protected $level = 0;

    /**
     * FlowNative constructor.
     *
     * @param Helper $helper
     */
    public function __construct(Helper $helper = null)
    {
        $this->helper     = $helper ?: new Helper();
        $this->extensions = new Extensions();
        $this->content    = new Context($this->helper, $this->extensions, $this);
    }

    /**
     * @return Extensions
     */
    public function extensions()
    {
        return $this->extensions;
    }

    /**
     * @param string $name
     * @param string $path
     *
     * @return $this
     */
    public function addFolder($name, $path)
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
    protected function folder($name, $path)
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
     * @return mixed
     */
    public function path($view)
    {
        list($bundle, $path) = explode(':', $view, 2);

        return $this->folder($bundle, $path);
    }

    /**
     * @param string $view
     * @param array  $arguments
     *
     * @return mixed
     */
    public function render($view, array $arguments = [])
    {

        if (!$this->level++)
        {
            $content = clone $this->content;
        }

        $callable = function ($__flow__view)
        {
            ob_start();
            extract($this->exports());
            require $__flow__view;

            foreach ($this->native->extensions()->blocks()->getExtends() as $__flow__extend)
            {
                echo trim($this->native->render($__flow__extend));
            }

            return trim(ob_get_clean());
        };

        $render = $callable->call(
            $this->content->mergeData($arguments),
            $this->path($view),
            $this
        );

        if (!--$this->level)
        {
            $this->content = $content;
        }

        return $render;
    }

}
