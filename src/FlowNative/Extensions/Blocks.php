<?php

namespace Bavix\FlowNative\Extensions;

use Bavix\Exceptions\Invalid;
use Bavix\Exceptions\NotFound\Data;
use Bavix\Exceptions\Runtime;

class Blocks
{

    const RESET   = 'reset';
    const APPEND  = 'append';
    const PREPEND = 'prepend';

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $extends;

    /**
     * @var array
     */
    protected $lastBlock = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $blocks = [];

    /**
     * @return null|string
     */
    public function getExtends()
    {
        $extends       = $this->extends;
        $this->extends = null;

        return $extends;
    }

    /**
     * @param string $layout
     * @param string $from
     */
    public function extends(string $layout, string $from = null)
    {
        $this->from    = $from;
        $this->extends = $layout;
    }

    /**
     * @param string $name
     * @param string $option
     */
    public function start(string $name, string $option = self::RESET)
    {
        \ob_start();

        if (empty($this->blocks[$name]))
        {
            $this->blocks[$name] = null;
        }

        $this->options[$name] = $option;
        $this->lastBlock[]    = $name;
    }

    /**
     * @return string|null
     */
    public function end()
    {

        if (empty($this->lastBlock))
        {
            throw new Data('Not found open blocks');
        }

        $pop    = \array_pop($this->lastBlock);
        $option = $this->options[$pop];
        $data   = \trim(\ob_get_clean());

        switch ($option)
        {
            case self::RESET:
                if (empty($this->blocks[$pop]) || $this->extends)
                {
                    $this->blocks[$pop] = $data;
                }
                break;

            case self::APPEND:
                $this->blocks[$pop] .= $data;
                break;

            case self::PREPEND:
                $this->blocks[$pop] = $data . $this->blocks[$pop];
                break;

            default:
                throw new Invalid('Undefined type `' . $option . '` block `' . $pop . '`');
        }

        if (null === $this->extends)
        {
            return $this->blocks[$pop];
        }

        return null;

    }

}
