<?php

namespace Bavix\FlowNative;

use Bavix\Exceptions\NotFound;

class Context
{

    /**
     * @var array
     */
    protected $___data___ = [];

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var FlowNative
     */
    protected $native;

    /**
     * @var Extensions
     */
    protected $ext;

    /**
     * Context constructor.
     *
     * @param Helper $helper
     * @param Extensions $ext
     */
    public function __construct(Helper $helper, Extensions $ext)
    {
        $this->helper = $helper;
        $this->ext    = $ext;
    }

    /**
     * @param FlowNative $flowNative
     *
     * @return $this
     */
    public function setFlow(FlowNative $flowNative): self
    {
        $this->native = $flowNative;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function mergeData(array $data): self
    {
        $this->___data___ = \array_merge($this->___data___, $data);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function exists($name): bool
    {
        return
            isset($this->___data___[$name]) ||
            \array_key_exists($name, $this->___data___);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function &__get($name)
    {
        return $this->___data___[$name];
    }

    /**
     * @param string $name
     * @param mixed  $mixed
     */
    public function __set($name, $mixed)
    {
        $this->___data___[$name] = $mixed;
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

}
