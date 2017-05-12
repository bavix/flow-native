<?php

namespace Native;

include_once dirname(__DIR__) . '/vendor/autoload.php';

class Context {
    protected $readOnly = false;
    protected $data = [];
    public function marge(\Bavix\FlowNative\Helper $helper, array $arguments)
    {
        foreach ($arguments as $argument => $value)
        {
            $this->data[$helper->substr($this, $argument, 1)] = $helper->substr($this, $value, 1);
        }

        return $this;
    }
}

$helper = new \Bavix\FlowNative\Helper();
$helper->add('var_dump', 'var_dump');
$helper->add('substr', 'mb_substr');
$helper->add('data', function ($helper, ...$arguments) {
    return $this->marge($helper, ...$arguments);
});

$content = new Context();

$helper->data($content, $helper, [' hello' => ' world']);
$helper->var_dump($content, $content);

$helper->add('prop', function ($object, $path)
{
    if (empty($path))
    {
        throw new \Bavix\Exceptions\Blank('Path is empty');
    }

    $queue = new \Bavix\Foundation\Arrays\Queue(
        \Bavix\Helpers\Arr::keys($path)
    );

    while (!$queue->isEmpty())
    {
        $prop = $queue->pop();

        if (is_object($object))
        {
            if (method_exists($object, $prop))
            {
                $object = $object->$prop();

                continue;
            }

            $object = $object->$prop;

            continue;
        }

        $object = $object[$prop];
    }

    return $object;
});

$mixed = (object)[
    'a' => [
        'b' => (object)[
            'c' => 'Max'
        ]
    ]
];

var_dump($helper->prop($content, $mixed, 'a.b'));
var_dump($helper->prop($content, $mixed, 'a'));
var_dump($helper->prop($content, $mixed, 'a.b.c'));
