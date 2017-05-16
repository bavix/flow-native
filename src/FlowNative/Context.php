<?php

namespace Bavix\FlowNative;

use Bavix\Exceptions\NotFound;

class Context
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var FlowNative
     */
    protected $flow;

    /**
     * @var Extensions
     */
    protected $ext;

    /**
     * Context constructor.
     *
     * @param Helper $helper
     * @param Extensions $ext
     * @param FlowNative $flow
     */
    public function __construct(Helper $helper, Extensions $ext, FlowNative $flow)
    {
        $this->helper = $helper;
        $this->flow = $flow;
        $this->ext = $ext;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function mergeData(array $data)
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function exists($name)
    {
        return
            isset($this->data[$name]) ||
            \array_key_exists($name, $this->data);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (!$this->exists($name))
        {
            throw new NotFound\Data('Variable `' . $name . '` not found');
        }

        return $this->data[$name];
    }

    /**
     * @param string $name
     * @param mixed  $mixed
     */
    public function __set($name, $mixed)
    {
        $this->data[$name] = $mixed;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return $this->exists($name);
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        return $this->helper->$name($this, ...$arguments);
    }

}
