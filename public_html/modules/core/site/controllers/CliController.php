<?php

abstract class CliController extends CoreController
{
    /**
     * @var \CliLogger
     */
    protected $logger;

    /**
     * @inheritdoc
     */
    public static function delegate($sAction, $sCacheControllerKey = '')
    {
        if (DEBUG || isDeveloper() || Request::isCli()) {
            return parent::delegate($sAction, $sCacheControllerKey);
        } else {
            return Router::httpError(404, false);
        }
    }

    /**
     * @inheritdoc
     *
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        $this->logger = CliLogger::create();

        $this->end();
    }

    /**
     * Log a message
     *
     * @param string $sMessage [optional]
     * @param mixed  $m1       [optional] parameter 1 to use in sprintf
     * @param mixed  $mN       [optional] parameter N to use in sprintf
     */
    protected function log(...$mMessage)
    {
        $this->logger->log(...$mMessage);
    }

    /**
     * @inheritdoc
     */
    public function render($template = null, $module = 'core')
    {
        switch (static::$render) {
            case static::RENDER_FALSE:
                $this->logger->output();

                return null;
            default:
                return parent::render($template, $module);
        }
    }
}