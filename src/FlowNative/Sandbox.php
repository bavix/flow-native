<?php

namespace Bavix\FlowNative;

use Bavix\Exceptions\Runtime;
use Bavix\Helpers\Closure;

class Sandbox
{

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var Context
     */
    protected $content;

    /**
     * Sandbox constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context, $limit = 4096)
    {
        $this->content = $context;
        $this->limit   = $limit;
    }

    /**
     * @return Context
     */
    public function content(): Context
    {
        return $this->content;
    }

    /**
     * @param string $view
     * @param array  $arguments
     *
     * @return string
     */
    public function execute(string $view, array $arguments)
    {
        /**
         * @var Closure $sandbox
         * @var Context $content
         */

        /**
         * @param string $__flow__view
         *
         * @return string
         */
        $sandbox = function (string $__flow__view)
        {
            include $this->native->path($__flow__view);

            return $this->ext->blocks()->getExtends();
        };

        $content  = clone $this->content;
        $_view    = $view;
        $iterator = 0;

        $content->mergeData($arguments);

        do
        {
            if ($iterator >= $this->limit)
            {
                throw new Runtime('The application is not responding');
            }

            $iterator++;

            $_view = $sandbox->call(
                $content,
                $_view
            );
        }
        while ($_view);
    }

}
