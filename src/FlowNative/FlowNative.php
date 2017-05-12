<?php

namespace Bavix\FlowNative;

use Bavix\Exceptions\NotFound;

class FlowNative
{

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
     * FlowNative constructor.
     *
     * @param Helper $helper
     */
    public function __construct(Helper $helper = null)
    {
        $this->helper  = $helper ?: new Helper();
        $this->content = new Context($this->helper);
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
    protected function path($view)
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
        $callable = function ($view)
        {
            ob_start();
            require $view;

            return ob_get_clean();
        };

        return $callable->call(
            $this->content->mergeData($arguments),
            $this->path($view)
        );
    }

}
