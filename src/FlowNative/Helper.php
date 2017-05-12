<?php

namespace Bavix\FlowNative;

class Helper
{

    /**
     * @var array
     */
    protected $helpers;

    /**
     * @param string   $name
     * @param callable $callable
     *
     * @return $this
     */
    public function add($name, callable $callable)
    {
        $this->helpers[$name] = $callable;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->helpers[$name]);
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        $newThis  = array_shift($arguments);
        $callable = $this->helpers[$name];

        if (method_exists($callable, 'call'))
        {
            /**
             * @var \Closure $callable
             */
            return $callable->call($newThis, ...$arguments);
        }

        return $callable(...$arguments);
    }

}
