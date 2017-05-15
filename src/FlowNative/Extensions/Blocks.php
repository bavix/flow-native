<?php

namespace Bavix\FlowNative\Extensions;

use Bavix\Exceptions\Invalid;
use Bavix\Exceptions\NotFound\Data;

const RESET   = 0;
const APPEND  = 1;
const PREPEND = 2;

class Blocks
{

    protected $extends   = [];
    protected $lastBlock = [];
    protected $options   = [];
    protected $blocks    = [];

    /**
     * @return array
     */
    public function getExtends()
    {
        $extends       = $this->extends;
        $this->extends = [];

        return $extends;
    }

    /**
     * @param      $layout
     * @param null $from
     */
    public function extends ($layout, $from = null)
    {
        if (!$from)
        {
            $debug = \debug_backtrace();
            $from  = $debug[0]['file'];
        }

        $this->extends[$from] = $layout;
    }

    public function start($name, $option = RESET)
    {

        ob_start();

        if (empty($this->blocks[$name]))
        {
            $this->blocks[$name] = null;
        }

        $this->options[$name] = $option;
        $this->lastBlock[]    = $name;

    }

    public function end()
    {

        if (empty($this->lastBlock))
        {
            throw new Data('Not found open blocks');
        }

        $pop    = array_pop($this->lastBlock);
        $option = $this->options[$pop];
        $data   = ob_get_clean();

        switch ($option)
        {
            case RESET:
                if (empty($this->blocks[$pop]))
                {
                    $this->blocks[$pop] = $data;
                }
                break;

            case APPEND:
                $this->blocks[$pop] .= $data;
                break;

            case PREPEND:
                $this->blocks[$pop] = $data . $this->blocks[$pop];
                break;

            default:
                throw new Invalid('Undefined type `' . $option . '` block `' . $pop . '`');
        }

        if (empty($this->extends))
        {
            return $this->blocks[$pop];
        }

        return null;

    }

}
