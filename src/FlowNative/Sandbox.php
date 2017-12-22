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
    public function execute(string $view, array $arguments): string
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
        $sandbox = function (string $__flow__view) {

            /**
             * @var Context $this
             */
            \extract($this->exports(), EXTR_REFS);

//            \ob_start();
            require $this->native->path($__flow__view);

            return //[
//                \trim(\ob_get_clean()),
                $this->ext->blocks()->getExtends()//            ];
                ;
        };

        \ob_start();

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

        return \trim(\ob_get_clean());
    }

}
