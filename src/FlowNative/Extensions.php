<?php

namespace Bavix\FlowNative;

use Bavix\Kernel\Resolver;

/**
 * Class Extensions
 *
 * @package Bavix\FlowNative
 *
 * @method Extensions\Blocks blocks()
 */
class Extensions extends Resolver
{

    protected $lumperMap = [
        'blocks' => Extensions\Blocks::class
    ];

}
