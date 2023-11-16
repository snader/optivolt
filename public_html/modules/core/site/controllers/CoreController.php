<?php

abstract class CoreController
{
    /**
     * Constants rendering
     *
     */
    const RENDER_TRUE  = 'true';
    const RENDER_FALSE = 'false';
    const RENDER_JSON  = 'json';
    const RENDER_JSEND = 'jsend';
    const RENDER_FILE  = 'file';

    /**
     * Current controller instance
     *
     * @var \CoreController
     */
    protected static $instance;

    /**
     * Render the actions
     *
     * @var bool
     */
    protected static $render = self::RENDER_TRUE;

    /**
     * Render engine
     *
     * @var RendererInterface
     */
    protected static $renderer;

    /**
     * Controller key cache
     *
     * @var string
     */
    protected $cacheControllerKey;

    /**
     * JSend status
     *
     * @var string
     */
    protected static $jsendStatus;

    /**
     * File contents
     *
     * @var string
     */
    protected static $fileContent;

    /**
     * File content type
     *
     * @var string
     */
    protected static $fileContentType;

    /**
     * File name
     *
     * @var string
     */
    protected static $fileName;

    /**
     * List of allowed actions
     *
     * @var array
     */
    protected static $allowed_actions = [];

    /**
     * Is this controller AMP aware
     *
     * @var bool
     */
    protected static $ampEnabled = false;

    /**
     * Retrieve the current controller
     *
     * @return static
     */
    public static function getCurrent()
    {
        return static::$instance;
    }

    /**
     * Delegate to this controller
     *
     * @param string $sAction
     * @param string $sCacheControllerKey
     *
     * @return static
     */
    public static function delegate($sAction, $sCacheControllerKey = '')
    {
        $oController = new static($sCacheControllerKey);

        static::$instance = $oController;

        /* check if method `checkMaintanance` can be called */
        if(method_exists($oController, 'checkMaintanance')) {
            $oController->checkMaintanance();
        }

        if ($oController->isActionAllowed($sAction)) {
            $oController->init();
            $oController->{$sAction}();
        } else {
            Request::setAction(Request::ACTION_INDEX, Request::ACTION_INDEX);
            $oController->init();
            $oController->index();
        }

        return $oController;
    }

    /**
     * Is the given action allowed
     *
     * @param $action
     *
     * @return bool
     */
    public function isActionAllowed($action)
    {
        return in_array($action, static::$allowed_actions);
    }

    /**
     * CoreController constructor.
     *
     * @param string $sCacheControllerKey
     */
    public function __construct($sCacheControllerKey)
    {
        array_unshift(static::$allowed_actions, 'index');

        $this->cacheControllerKey = $sCacheControllerKey;
    }

    /**
     * Class initializer
     */
    public function init()
    {
        if (Request::isAmp() && !static::$ampEnabled) {
            Router::redirect(getCurrentUrlPath(true, true, true));
        }
        $this->setRenderEngine(new LegacyRenderer());
    }

    /**
     * Index action
     *
     */
    public abstract function index();

    /**
     * Render the controller
     *
     * @param string|null $template
     * @param string      $module
     *
     * @return string|null
     */
    public function render($template = null, $module = 'core')
    {
        switch (static::$render) {
            case static::RENDER_TRUE:
                $this->getRenderEngine()
                    ->setVariables(
                        [
                            'sCacheControllerKey' => $this->cacheControllerKey,
                            'oController'         => $this,
                        ]
                    );

                return $this->getRenderEngine()
                    ->render($template, false, $module);
            case static::RENDER_FALSE:
                return null;
            case static::RENDER_JSON:
                header('Content-type: text/json');

                return $this->getRenderEngine()
                    ->renderJson();
            case static::RENDER_JSEND:
                header('Content-type: text/json');

                return $this->getRenderEngine()
                    ->renderJSend(static::$jsendStatus);
            case static::RENDER_FILE:
                return $this->getRenderEngine()
                    ->renderFile(
                        static::$fileContent,
                        static::$fileContentType,
                        static::$fileName
                    );
        }
    }

    /**
     * Retrieve the render engine
     *
     * @return \RendererInterface
     */
    public function getRenderEngine()
    {
        return static::$renderer;
    }

    /**
     * Set the render engine
     *
     * @param \RendererInterface $renderer
     */
    public function setRenderEngine(RendererInterface $renderer)
    {
        static::$renderer = $renderer;
    }

    /**
     * Prevent rendering
     *
     */
    protected function end()
    {
        static::$render = static::RENDER_FALSE;
    }

    /**
     * Render as JSON
     *
     */
    protected function json()
    {
        static::$render = static::RENDER_JSON;
    }

    /**
     * Render as JSend
     *
     * @param string $status
     */
    protected function jsend($status = JSendResponse::STATUS_SUCCESS)
    {
        static::$render      = static::RENDER_JSEND;
        static::$jsendStatus = $status;
    }

    /**
     * Render as file
     *
     * @param string      $sContent
     * @param string      $sContentType
     * @param string|null $sFileName
     */
    protected function file($sContent, $sContentType, $sFileName = null)
    {
        static::$render          = static::RENDER_FILE;
        static::$fileContent     = $sContent;
        static::$fileContentType = $sContentType;
        static::$fileName        = $sFileName;
    }

    /**
     * Set a header value
     *
     * @param string $sHeader
     */
    public function setHeader($sHeader, $sContent)
    {
        $this->getRenderEngine()->setHeader($sHeader, $sContent);
    }

    /**
     * Set header values
     *
     * @param string[] $aHeaders
     */
    public function setHeaders(array $aHeaders)
    {
        $this->getRenderEngine()->setHeaders($aHeaders);
    }

    /**
     * Clear a specific header value
     *
     * @param string $sHeader
     */
    public function clearHeader($sHeader)
    {
        $this->getRenderEngine()->clearHeader($sHeader);
    }

    /**
     * Clear all header values
     *
     */
    public function clearHeaders()
    {
        $this->getRenderEngine()->clearHeaders();

    }
}