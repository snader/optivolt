<?php

class Router
{
    /**
     * Constants scope
     *
     */
    const SCOPE_ADMIN  = 'dashboard';
    const SCOPE_PUBLIC = '';

    /**
     * Constants controller location
     *
     */
    const CONTROLLER_LOCATION_ADMIN  = 'admin';
    const CONTROLLER_LOCATION_PUBLIC = 'site';

    /**
     * Constants controller prefix
     *
     */
    const CONTROLLER_PREFIX_ADMIN  = 'Admin';
    const CONTROLLER_PREFIX_PUBLIC = '';

    /**
     * Constants route manifests
     *
     */
    const ROUTES_MANIFEST_PUBLIC = DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'init' . DIRECTORY_SEPARATOR . 'siteRoutes.json';
    const ROUTES_MANIFEST_ADMIN  = DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'init' . DIRECTORY_SEPARATOR . 'adminRoutes.json';

    /**
     * Constant login controller
     */
    const LOGIN_CONTROLER = 'login';

    /**
     * Scope
     *
     * @var string
     */
    protected static $scope = self::SCOPE_PUBLIC;

    /**
     * Request
     *
     * @var Request
     */
    protected static $request;

    /**
     * Parameters
     *
     * @var array
     */
    protected static $parameters;

    /**
     * Perform the routing
     *
     * @param array|null $aParams
     *
     * @return string
     */
    public static function route($aParams = null)
    {



        if (Request::isCli()) {
            static::parseScriptParams($aParams);
        }

        // @note: legacy
        if (Request::getVar('logout')) {
            UserManager::logout(ADMIN_FOLDER . '/');
        }
        if (moduleExists('cookieConsent')) {
            if (Request::getVar('reset-cookies')) {
                //reset all cookies except core cookies
                PolicyCookie::destroy();
                Router::redirect(getCurrentUrlPath(false, false, true));
            }
        }
        // make flush available for all URL's
        if (Request::getVar('flush')) {

            switch (Request::getVar('flush')) {
                case 'all':
                default:
                    Cache::flushAll();
                    break;
                case 'cache':
                    Cache::flushCache();
                    break;
            }
        }

        if (Request::isNotCli()) {

            static::redirectByPatternAndType();
        }



        Locales::initialize();
        AdminLocales::initialize();

        return static::delegateController();
    }

    /**
     * Parse the request url
     *
     */
    public static function parseRequestUri()
    {
        $sRequestUri = Server::get('SERVER_NAME') . Server::get('REQUEST_URI');
        $sRequestUri = str_replace(Locales::getCurrentURLFormat(), '', $sRequestUri);
        $sRequestUri = cleanUrlPath($sRequestUri);

        static::$parameters = explode('/', $sRequestUri);
        static::$parameters = array_values(array_filter(static::$parameters));
    }

    /**
     * Parse the script parameters
     *
     * @param array $aParams
     */
    public static function parseScriptParams($aParams)
    {
        // drop the script name
        array_shift($aParams);

        // get the request
        if ($sRequest = array_shift($aParams)) {
            $sRequest = '/' . $sRequest;
        }

        // if we have parameters, add them
        if (count($aParams)) {
            $sRequest .= '?' . implode('&', $aParams);
        }

        // set these two required server parameters
        Server::set('SERVER_NAME', CLIENT_URL);
        Server::set('REQUEST_URI', $sRequest);

        // while we have parameters, add them to the GET variables list
        while ($sParams = array_shift($aParams)) {
            parse_str($sParams, $aGet);
            Request::setGet($aGet);
        }
    }

    /**
     * Attempt to redirect
     *
     */
    public static function redirectByPatternAndType()
    {
        if (moduleExists('redirect')) {
            #Check if current URL is stored in Database for redirecting
            #First run trough the specific redirects
            #When matching, redirect to the new URL
            #If not matching, run trough the regular expressions
            $oRedirect = RedirectManager::getRedirectByPatternAndType(getCurrentUrlPath(true, true, true, true), Redirect::TYPE_SPECIFIC);
            if ($oRedirect) {
                static::redirect($oRedirect->newUrl, false, true);
            } else {
                $aRedirects = RedirectManager::getRedirectsByFilter(["type" => Redirect::TYPE_EXPRESSION]);
                if (!empty($aRedirects)) {
                    foreach ($aRedirects as $oRedirect) {
                        $sUrl     = getCurrentUrlPath(true, true);
                        $sPattern = preg_replace('/#/', '\#', $oRedirect->pattern);
                        if (preg_match('#' . $sPattern . '#i', $sUrl)) {
                            static::redirect(preg_replace('#' . $sPattern . '#i', $oRedirect->newUrl, $sUrl));
                        }
                    }
                }
            }
        }
    }

    /**
     * Delegate to controller
     *
     * @return string
     */
    public static function delegateController()
    {
        static::parseRequestUri();

        $bScope = false;
        // retrieve the scope (public or admin) from the parameters
        $sScope = array_shift(static::$parameters);
        switch($sScope) {
            case static::SCOPE_ADMIN:
                static::$scope = $sScope;
                break;
            case Request::MODE_AMP:
                Request::setAmpMode(true);
                break;
            default:
                array_unshift(static::$parameters, $sScope);
                break;
        }

        // we'll always need a parameter
        if (empty(static::$parameters)) {
            static::$parameters = [''];
        }

        // delegate according to scope
        switch (static::$scope) {
            case static::SCOPE_PUBLIC:
                // if the request is not a CLI request and security is enabled and we do not have a logged in user
                if ((Request::isNotCli() && Environment::isSecurityEnabled() && !UserManager::getCurrentUser())) {
                   // Session::set("loginReferrer", getCurrentUrl());
                    //static::redirect(Director::getBaseURL(static::SCOPE_ADMIN, 'login'));
                }
                return static::delegatePublicController();
            case static::SCOPE_ADMIN:
                return static::delegateAdminController();
        }
    }

    /**
     * Delegate to public controller
     *
     * @return string
     */
    protected static function delegatePublicController()
    {
        $aRoutes = static::getPublicRouteManifest();

        // rtl traversing the request parameters to find the first controller
        $aParams = static::$parameters;
        $sAction = Request::ACTION_INDEX;
        do {
            $sPath = implode('/', $aParams);
            if (array_key_exists($sPath, $aRoutes)) {
                return static::delegateControllerByData($aRoutes[$sPath], $sPath, $sAction);
            }
            $sAction = array_pop($aParams);
        } while ($sAction);

        // we grab pages by the full path
        $sUrlPath = sprintf('/%1$s', implode('/', static::$parameters));
        $sUrlPath = cleanUrlPath($sUrlPath);

        return static::delegateControllerByUrlPath($sUrlPath);
    }

    /**
     * Retrieve the admin controller
     *
     * @return string
     */
    protected static function delegateAdminController()
    {



        $oCurrentUser = UserManager::getCurrentUser();

        if (!$oCurrentUser && getCurrentUrlPath() != ADMIN_FOLDER . '/login' && getCurrentUrlPath() != ADMIN_FOLDER . '/login/2-step-authentication') {
            Session::flash('loginReferrer', getCurrentUrlPath(true, true));
            static::redirect(ADMIN_FOLDER . '/login');
        }

        if ($oCurrentUser && !UserManager::isUserAccessAllowed($oCurrentUser->userId)) {
            UserManager::logout(ADMIN_FOLDER . '/login');
        }

        $aRoutes = static::getAdminRouteManifest();



        // rtl traversing the request parameters to find the first controller
        $aParams = static::$parameters;
        $sAction = Request::ACTION_INDEX;
        $sPath = implode('/', $aParams);


        do {
            $sPath = implode('/', $aParams);
            if (array_key_exists($sPath, $aRoutes) || array_key_exists(strtolower($sPath ?? ''), $aRoutes)) {

                return static::delegateControllerByData($aRoutes[$sPath], $sPath, $sAction);
            }
            $sAction = array_pop($aParams);
        } while ($sAction);

        return static::httpError(404, true);
    }

    /**
     * Delegate to controller by given data
     *
     * @param array  $aData
     * @param string $sControllerSegment
     * @param string $sActionSegment
     *
     * @return string
     */
    protected static function delegateControllerByData(array $aData, $sControllerSegment, $sActionSegment)
    {
        $sControllerName = sprintf('%1$s%2$sController', StringHelper::toPascalCase($aData['controller']), static::isAdminScope() ? static::CONTROLLER_PREFIX_ADMIN : static::CONTROLLER_PREFIX_PUBLIC);
        $sActionName     = StringHelper::toCamelCase($sActionSegment);

        Request::setController($sControllerName, $sControllerSegment);
        Request::setAction($sActionName, $sActionSegment);
        Request::setParameters(static::$parameters);


        if (static::isAdminScope() && UserManager::getCurrentUser() && !empty($sControllerSegment)) {
            if (!UserManager::getCurrentUser()->hasRightsForModuleByControllerSegment($sControllerSegment)) {
                Router::redirect(ADMIN_FOLDER);
            }
        }

        if (class_exists($sControllerName)) {
            try {
                /** @var \CoreController $oController */
                $oController = $sControllerName::delegate($sActionName, $sControllerSegment);
                if (static::isAdminScope()) {
                    /** @var \AdminController $oController */
                    $oController->setCurrentUser(Session::get('oCurrentUser'));
                }

                return $oController->render();
            } catch (Exception $e) {
                Debug::logError($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());

                return static::httpError(404, true);
            }
        }

        // @note legacy
        $sControllerPath = sprintf(
            '%2$s%1$s%3$s%1$s%5$s%1$scontrollers%1$s%4$s.cont.php',
            DIRECTORY_SEPARATOR,
            SYSTEM_MODULES_FOLDER,
            $aData['module'],
            $aData['controller'],
            static::isAdminScope() ? static::CONTROLLER_LOCATION_ADMIN : static::CONTROLLER_LOCATION_PUBLIC
        );

        return static::legacyRoute($sControllerPath, ['oCurrentUser' => Session::get('oCurrentUser', null)]);
    }

    /**
     * Delegate to controller by URL path
     *
     * @param string $sUrlPath
     *
     * @return string
     */
    protected static function delegateControllerByUrlPath($sUrlPath)
    {
        if (!moduleExists('pages')) {
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
            die();
        }

        // @note legacy, PageManager needs work
        $aUrlPath = explode('/', trim($sUrlPath, '/'));

        $sActionSegment = Request::ACTION_INDEX;
        // RtL lookup of page
        do {
            $oPage = PageManager::getPageByUrlPath('/' . implode('/', $aUrlPath), false, Locales::language());
            if ($oPage) {
                if (!$oPage->online) {
                    return static::httpError(404, true);
                }
                $sControllerPath = $oPage->getControllerPath();
            } else {
                $sActionSegment = array_pop($aUrlPath);
            }
        } while (empty($sControllerPath) && $aUrlPath);

        if (empty($sControllerPath)) {
            $aRedirectModules = [
                'VacancyManager',
                'EmployeeManager',
                'PageManager',
            ];

            # define modules for redirects to search in
            $sRedirectPath = null;
            foreach ($aRedirectModules as $sModule) {
                if (!moduleExists($sModule)) {
                    continue;
                }

                $oRedirectModule = new $sModule();
                $sRedirectPath   = $oRedirectModule->getRedirectUrlPathByUrlPath(getCurrentUrlPath());
                if (!empty($sRedirectPath)) {
                    break;
                }
            }

            // redirect if there is a path
            if ($sRedirectPath) {
                // 301 redirect
                static::redirect($sRedirectPath, false, true);
            }

            return static::httpError(404, true);
        } else {
            $iSlashPos          = strrpos($sControllerPath, '/') + 1;
            $iDotPos            = strpos($sControllerPath, '.') - $iSlashPos;
            $sControllerSegment = substr($sControllerPath, $iSlashPos, $iDotPos);
            $sControllerName    = sprintf('%1$sController', StringHelper::toPascalCase($sControllerSegment));
            $sActionName        = StringHelper::toCamelCase($sActionSegment);

            Request::setController($sControllerName, static::$parameters[0]);
            Request::setAction($sActionName, $sActionSegment);
            Request::setParameters(static::$parameters);
            if (class_exists($sControllerName)) {
                try {
                    return $sControllerName::delegate($sActionName, $sControllerSegment)
                        ->render();
                } catch (Exception $e) {
                    Debug::logError($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());

                    return static::httpError(404, true);
                }
            }

            // @note legacy
            return static::legacyRoute(DOCUMENT_ROOT . $sControllerPath, ['sControllerSegment' => $sControllerSegment]);
        }
    }

    /**
     * Retrieve the public route manifest
     *
     * @return array
     */
    public static function getPublicRouteManifest()
    {
        return static::getRouteManifest(static::ROUTES_MANIFEST_PUBLIC, 'public controller routes error');
    }

    /**
     * Retrieve the admin route manifest
     *
     * @return array
     */
    public static function getAdminRouteManifest()
    {
        return static::getRouteManifest(static::ROUTES_MANIFEST_ADMIN, 'admin controller routes error');
    }

    /**
     * Retrieve the contents of the route manifest
     *
     * @param string $sFile
     * @param string $sError
     *
     * @return array
     */
    protected static function getRouteManifest($sFile, $sError)
    {
        $sFile     = FileSystem::getOrTouchFile($sFile);
        $sContents = FileSystem::read($sFile);

        $aRoutes = json_decode($sContents, true);
        if ($aRoutes === false) {
            die($sError);
        }

        return $aRoutes;
    }

    /**
     * Redirect to the given url
     *
     * @param string $sRedirectURL
     * @param bool   $bUtm
     * @param bool   $b301
     */
    public static function redirect($sRedirectURL, $bUtm = false, $b301 = false)
    {

        if ($bUtm) {
            $aUtm = [];
            if (($sSource = Request::getVar('utm_source')) && !strpos($sRedirectURL, 'utm_source=')) {
                array_push($aUtm, sprintf('utm_source=%1$s', $sSource));
            }
            if (($sMedium = Request::getVar('utm_medium')) && !strpos($sRedirectURL, 'utm_medium=')) {
                array_push($aUtm, sprintf('utm_source=%1$s', $sSource));
            }
            if (($sCampaign = Request::getVar('utm_campaign')) && !strpos($sRedirectURL, 'utm_campaign=')) {
                array_push($aUtm, sprintf('utm_campaign=%1$s', $sCampaign));
            }
            if (($sTerm = Request::getVar('utm_term')) && !strpos($sRedirectURL, 'utm_term=')) {
                array_push($aUtm, sprintf('utm_term=%1$s', $sTerm));
            }
            if (($sContent = Request::getVar('utm_content')) && !strpos($sRedirectURL, 'utm_content=')) {
                array_push($aUtm, sprintf('utm_content=%1$s', $sContent));
            }
            $sRedirectURL .= (strpos($sRedirectURL, '?') ? '&' : '?') . implode('&', $aUtm);
        }
        if ($b301) {
            header("HTTP/1.1 301 Moved Permanently");
        }

        header("Location: " . $sRedirectURL);
        die();
    }

    /**
     * Legacy method of working with controllers
     *
     * @param string $sPath
     * @param array  $aVariables
     *
     * @return string
     *
     * @note legacy
     */
    protected static function legacyRoute($sPath, $aVariables)
    {

        // legacy controllers have no actions
        Request::setAction(Request::ACTION_INDEX, Request::ACTION_INDEX);
        ob_start();
        global $oPageLayout;
        extract($aVariables);
        if (static::$scope == static::SCOPE_ADMIN) {
            /** @var Locale[] $aLocales */
            $aLocales = LocaleManager::getLocalesByFilter(['showAll' => true]);
            /** @var User $oCurrentUser */
            $oCurrentUser = UserManager::getCurrentUser();
            if (!$oCurrentUser && Request::getControllerSegment() != static::LOGIN_CONTROLER) {
                Session::set('loginReferrer', getCurrentUrlPath());
                static::redirect(sprintf('/%1$s/%2$s', static::SCOPE_ADMIN, static::LOGIN_CONTROLER));
            }

        } else {
            $oLocale             = Locales::locale();
            $sCacheControllerKey = Request::getControllerSegment();
        }

        if (moduleExists('core')) {
            $oCurrentAccessLog = AccessLogManager::generateAccessLog();
        } else {
            $oCurrentAccessLog = null;
        }

        include $sPath;

        return ob_get_clean();
    }

    /**
     * @param int  $iErrorNr
     * @param bool $bRender
     *
     * @return string|\ErrorsController
     *
     * @note legacy, error controller needs rework
     */
    public static function httpError($iErrorNr, $bRender = false)
    {
        if (moduleExists('pages')) {
            $oController = ErrorsController::delegate(Request::ACTION_INDEX, 'errors', $iErrorNr);
            if ($bRender) {
                return $oController->render();
            }

            return $oController;
        }

        die('Page not found');
    }

    /**
     * Is the global scope admin
     *
     * @return bool
     */
    public static function isAdminScope()
    {
        return static::$scope == static::SCOPE_ADMIN;
    }

    /**
     * Is the global scope public
     *
     * @return bool
     */
    public static function isPublicScope()
    {
        return static::$scope == static::SCOPE_PUBLIC;
    }
}
