<?php

class ErrorsController extends PageController
{
    /**
     * @var int
     */
    protected $error;

    /**
     * Delegate to this controller
     *
     * @param string $sAction
     * @param string $sCacheControllerKey
     * @param int    $iErrorNr
     *
     * @return static
     */
    public static function delegate($sAction, $sCacheControllerKey = '', $iErrorNr = 404)
    {
        /** @var \ErrorsController $oController */
        $oController = new static($sCacheControllerKey);
        $oController->setErrorNr($iErrorNr);

        static::$instance = $oController;

        if ($oController->isActionAllowed($sAction)) {
            $oController->init();
            $oController->{$sAction}();
        } else {
            $oController->init();
            $oController->index();
        }

        return $oController;
    }

    /**
     * Set the error number
     *
     * @param int $iErrorNr
     */
    public function setErrorNr($iErrorNr)
    {
        $this->error = $iErrorNr;
    }

    /**
     * @inheritdoc
     */
    public function index($sRequestURL = null)
    {
        $sExtraInfo = 'Link: ' . getCurrentUrl() . '<br />';
        switch ($this->error) {
            case 403:
                header($_SERVER['SERVER_PROTOCOL'] . " 403 Forbidden"); //set the header to make it a real error
                $oPage = PageManager::getPageByUrlPath('/403');
                break;
            case 404:
            default:
                header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found"); //set the header to make it a real error
                $oPage      = PageManager::getPageByUrlPath('/404');
                $sExtraInfo .= 'Page with link: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '-');
                break;
        }

        # check if error need to be logged
        if ($this->shouldLog()) {
            $this->logError($sExtraInfo);
        }

        if (!$oPage) {
            die('Page could not be found');
        }

        $this->setMeta($oPage);

        # Get submenu structure
        if ($oPage->level > 1) {
            $oPageForMenu = PageManager::getPageByUrlPath('/' . http_get('controller'));
        } else {
            $oPageForMenu = $oPage;
        }

        $this->getRenderEngine()
            ->setVariables(
                [
                    'oPage'        => $oPage,
                    'oPageForMenu' => $oPageForMenu,
                    'aImages'      => $oPage->getImages(),
                    'aVideos'      => $oPage->getVideoLinks(),
                    'aFiles'       => $oPage->getFiles(),
                    'aLinks'       => $oPage->getLinks(),
                ]
            );
    }

    /**
     * Should we be logging
     *
     * @return bool
     */
    protected function shouldLog()
    {
        // some file extensions needs to be logged always and some only from own website
        $aExtensionsToLog = [
            'php',
            'html',
            'xhtml',
            'htm',
            '',
        ];
        $sExtension       = pathinfo($_SERVER['REQUEST_URI'], PATHINFO_EXTENSION);

        // if it is one of the controllers check if request came from own website and then log
        if (!in_array($sExtension, $aExtensionsToLog) && isset($_SERVER['HTTP_REFERER'])) {
            $sHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            if ($sHost && isset($_SERVER['HTTP_HOST']) && $sHost == $_SERVER['HTTP_HOST']) {
                return true;
            }
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            // do log if it's a physical link to this location
            return true;
        }

        /* start disabling logging of blacklisted URLs */
        if (class_exists('webservices')) {
            $aBlacklist404s = Blacklist404WebserviceManager::getBlacklist404s();
            if (!empty($aBlacklist404s)) {
                $sURLPath = getCurrentUrlPath(false, true);
                foreach ($aBlacklist404s AS $sExpression) {
                    if (@preg_match('#' . $sExpression . '#', $sURLPath)) {
                        return false;
                    }
                }
            }
        }
        /* end disabling logging of blacklisted URLs */

        // Log error if DEBUG is set to true
        if (DEBUG) {
            return true;
        }

        return false;
    }

    /**
     * Log the error
     *
     * @param string $sInfo
     */
    protected function logError($sInfo)
    {
        # send email with error details
        Debug::logError($this->error, "HTTP Error", __FILE__, __LINE__, $sInfo, Debug::LOG_IN_DATABASE);

        // only log errors to email if debug is true or DO_NOT_EMAIL_HTTP_ERRORS = false
        if (DEBUG || !DO_NOT_EMAIL_HTTP_ERRORS) {
            // Debug::logError($this->error, "HTTP Error", __FILE__, __LINE__, $sInfo, Debug::LOG_IN_EMAIL);
        }
    }
}