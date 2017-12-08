<?php

namespace Bavix\FlowNative;

use Bavix\Helpers\Closure;

class Sandbox
{

    protected $level = 0;

    /**
     * @var Context
     */
    protected $content;

    /**
     * Sandbox constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->content = $context;
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
        $sandbox = function (string $__flow__view): string
        {
            /**
             * @var Context $this
             */
            \extract($this->exports(), EXTR_REFS);

            \ob_start();
            require $this->native->path($__flow__view);

            foreach ($this->ext->blocks()->getExtends() as $__flow__block__extend)
            {
                echo \trim($this->native->render($__flow__block__extend));
            }

            return \trim(\ob_get_clean());
        };

        if (!$this->level++)
        {
            $content = clone $this->content;
        }

        // sandbox get responses
        $response = $sandbox->call(
            $this->content->mergeData($arguments),
            $view
        );

        if (!--$this->level)
        {
            $this->content = $content;
        }

        return $response;
    }

}
