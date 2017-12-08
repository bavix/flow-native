<?php

namespace Bavix\FlowNative;

use Bavix\Exceptions\NotFound;

class Helper
{

    /**
     * @var array
     */
    protected $helpers = [

        // string
        'substring'    => '\\Bavix\\Helpers\\Str::sub',
        'capitalize'   => '\\Bavix\\Helpers\\Str::capitalize',
        'upper'        => '\\Bavix\\Helpers\\Str::upp',
        'lower'        => '\\Bavix\\Helpers\\Str::low',
        'length'       => '\\Bavix\\Helpers\\Str::len',
        'shorten'      => '\\Bavix\\Helpers\\Str::shorten',
        'split'        => '\\Bavix\\Helpers\\Str::split',
        'ucFirst'      => '\\Bavix\\Helpers\\Str::ucFirst',
        'lcFirst'      => '\\Bavix\\Helpers\\Str::lcFirst',
        'random'       => '\\Bavix\\Helpers\\Str::random',
        'uniqid'       => '\\Bavix\\Helpers\\Str::uniqid',
        'fileSize'     => '\\Bavix\\Helpers\\Str::fileSize',
        'translit'     => '\\Bavix\\Helpers\\Str::translit',
        'first'        => '\\Bavix\\Helpers\\Str::first',
        'withoutFirst' => '\\Bavix\\Helpers\\Str::withoutFirst',
        'last'         => '\\Bavix\\Helpers\\Str::last',
        'withoutLast'  => '\\Bavix\\Helpers\\Str::withoutLast',
        'snakeCase'    => '\\Bavix\\Helpers\\Str::snakeCase',
        'camelCase'    => '\\Bavix\\Helpers\\Str::camelCase',
        'friendlyUrl'  => '\\Bavix\\Helpers\\Str::friendlyUrl',
        'toNumber'     => '\\Bavix\\Helpers\\Str::toNumber',
        'pos'          => '\\Bavix\\Helpers\\Str::pos',

        // array
        'in'           => '\\Bavix\\Helpers\\Arr::in',
        'range'        => '\\Bavix\\Helpers\\Arr::range',
        'merge'        => '\\Bavix\\Helpers\\Arr::merge',
        'shuffle'      => '\\Bavix\\Helpers\\Arr::shuffle',
        'keyExists'    => '\\Bavix\\Helpers\\Arr::keyExists',

        // number
        'randomInt'    => '\\Bavix\\Helpers\\Num::randomInt',
        'format'       => '\\Bavix\\Helpers\\Num::format',

        // json
        'jsonEncode'   => '\\Bavix\\Helpers\\JSON::encode',
        'jsonDecode'   => '\\Bavix\\Helpers\\JSON::decode',
    ];

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
    public function exists($name): bool
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
        $newThis = \array_shift($arguments);

        if (!$this->exists($name))
        {
            throw new NotFound\Data('Helper `' . $name . '` not found!');
        }

        $callable = $this->helpers[$name];

        if (\method_exists($callable, 'call'))
        {
            /**
             * @var \Closure $callable
             */
            return $callable->call($newThis, ...$arguments);
        }

        return $callable(...$arguments);
    }

}
