<?php

class Request
{
    use Initializable;

    /**
     * Constants request methods
     *
     */
    const REQUEST_METHOD_HEAD    = 'HEAD';
    const REQUEST_METHOD_GET     = 'GET';
    const REQUEST_METHOD_POST    = 'POST';
    const REQUEST_METHOD_PUT     = 'PUT';
    const REQUEST_METHOD_PATCH   = 'PATCH';
    const REQUEST_METHOD_DELETE  = 'DELETE';
    const REQUEST_METHOD_OPTIONS = 'OPTIONS';
    const REQUEST_METHOD_TRACE   = 'TRACE';
    const REQUEST_METHOD_CONNECT = 'CONNECT';

    /**
     * Constant action
     */
    const ACTION_INDEX = 'index';

    /**
     * Constant mode amp
     */
    const MODE_AMP = 'amp';

    /**
     * Get variable cache
     *
     * @var array
     */
    protected static $get;

    /**
     * Post variable cache
     *
     * @var array
     */
    protected static $post;

    /**
     * Files variable cache
     *
     * @var array
     */
    protected static $files;

    /**
     * Raw input
     *
     * @var string
     */
    protected static $raw;

    /**
     * Request URL
     *
     * @var string
     */
    protected static $url;

    /**
     * Request path
     *
     * @var string
     */
    protected static $path;

    /**
     * Request extension
     *
     * @var string
     */
    protected static $extension;

    /**
     * Raw parameters
     *
     * @var array
     */
    protected static $rawParameters = [];

    /**
     * Request variable cache
     *
     * @var array
     */
    protected static $parameters = [];

    /**
     * Current controller
     *
     * @var string
     */
    protected static $controller;

    /**
     * Current action
     *
     * @var string
     */
    protected static $action;

    /**
     * Controller segment
     *
     * @var string
     */
    protected static $controllerSegment;

    /**
     * Action segment
     *
     * @var string
     */
    protected static $actionSegment = self::ACTION_INDEX;

    /**
     * Parameter names
     *
     * @var array
     */
    protected static $params = [
        'ID',
        'OtherID',
        'AnotherID',
        'LastID',
    ];

    protected static $amp = false;

    /**
     * Initialize the request
     *
     */
    public static function init()
    {
        if (!static::isInitialized()) {
            static::$get   = $_GET;
            static::$post  = $_POST;
            static::$files = $_FILES;
            static::$raw   = file_get_contents('php://input');

            static::$url       = getCurrentUrlPath(false, true, true);
            static::$path      = getCurrentUrlPath();
            static::$extension = str_replace(static::$path, '', getCurrentUrlPath(false, true));

            static::initialize();
        }
    }

    /**
     * Set the current controller name
     *
     * @param string $sName
     * @param string $sSegment
     */
    public static function setController($sName, $sSegment = null)
    {
        static::$controller = $sName;
        if ($sSegment) {
            static::$controllerSegment = $sSegment;
        }
    }

    /**
     * Retrieve the current controller name
     *
     * @return string
     */
    public static function getController()
    {
        return static::$controller;
    }

    /**
     * Retrieve the controller segment
     *
     * @return string
     */
    public static function getControllerSegment()
    {
        return static::$controllerSegment;
    }

    /**
     * Set the current action
     *
     * @param string $sName
     * @param string $sSegment
     */
    public static function setAction($sName, $sSegment = null)
    {
        static::$action = $sName;
        if ($sSegment) {
            static::$actionSegment = $sSegment;
        }
    }

    /**
     * Retrieve the current action name
     *
     * @return string
     */
    public static function getAction()
    {
        return static::$action;
    }

    /**
     * Retrieve the action segment
     *
     * @return string
     */
    public static function getActionSegment()
    {
        return static::$actionSegment;
    }

    /**
     * Set the parameter names
     *
     * @param array $params
     */
    public static function setParameterNames(array $params)
    {
        static::$params     = $params;
        static::$parameters = [];
    }

    /**
     * Retrieve the get variables
     *
     * @return array
     */
    public static function getVars()
    {
        return static::$get;
    }

    /**
     * Retrieve a specific get variable
     *
     * @param string[] ...$aArguments
     *
     * @return null
     */
    public static function getVar(...$aArguments)
    {
        $sArgument = array_shift($aArguments);

        if (isset(static::$get[$sArgument])) {
            $mValue = static::$get[$sArgument];

            foreach ($aArguments as $sArgument) {
                if (isset($mValue[$sArgument])) {
                    $mValue = $mValue[$sArgument];
                } else {
                    return null;
                }
            }

            return $mValue;
        }

        return null;
    }

    /**
     * Retrieve the post variables
     *
     * @return array
     */
    public static function postVars()
    {
        return static::$post;
    }

    /**
     * Retrieve a specific post variable
     *
     * @param string[] ...$aArguments
     *
     * @return mixed
     */
    public static function postVar(...$aArguments)
    {
        $sArgument = array_shift($aArguments);

        if (isset(static::$post[$sArgument])) {
            $mValue = static::$post[$sArgument];

            foreach ($aArguments as $sArgument) {
                if (isset($mValue[$sArgument])) {
                    $mValue = $mValue[$sArgument];
                } else {
                    return null;
                }
            }

            return $mValue;
        }

        return null;
    }

    /**
     * Retrieve the files variables
     *
     * @return array
     */
    public static function files()
    {
        return static::$files;
    }

    /**
     * Retrieve a specific files variable
     *
     * @param string[] $aArguments
     *
     * @return mixed
     */
    public static function file(...$aArguments)
    {
        $sArgument = array_shift($aArguments);

        if (isset(static::$files[$sArgument])) {
            $mValue = static::$files[$sArgument];

            foreach ($aArguments as $sArgument) {
                if (isset($mValue[$sArgument])) {
                    $mValue = $mValue[$sArgument];
                } else {
                    return null;
                }
            }

            return $mValue;
        }

        return null;
    }

    /**
     * @return int|mixed
     */
    public static function getTotalFileUpload()
    {
        $iSize = 0;
        foreach (static::$files as $aFile) {
            if (is_array($aFile['size'])) {
                foreach ($aFile['size'] as $sSize) {
                    $iSize += $sSize;
                }
            } else {
                $iSize += $aFile['size'];
            }
        }

        return $iSize;
    }

    /**
     * Retrieve the request parameters
     *
     * @return array
     */
    public static function params()
    {
        static::prepareParameters();

        return static::$parameters;
    }

    /**
     * Retrieve a specific request parameter
     *
     * @param string $sName
     * @param null   $mDefault
     *
     * @return null
     */
    public static function param($sName, $mDefault = null)
    {
        static::prepareParameters();
        if (isset(static::$parameters[$sName])) {
            return static::$parameters[$sName];
        }

        return $mDefault;
    }

    /**
     * Retrieve the raw input
     *
     * @return string
     */
    public static function getRaw()
    {
        return static::$raw;
    }

    /**
     * Retrieve the request URL
     *
     * @return string
     */
    public static function getURL()
    {
        return static::$url;
    }

    /**
     * Retrieve the request path
     *
     * @return string
     */
    public static function getPath()
    {
        return static::$path;
    }

    /**
     * Retrieve the request extension
     *
     * @return string
     */
    public static function getExtension()
    {
        return static::$extension;
    }

    /**
     * Retrieve the request method
     *
     * @return mixed|null
     */
    public static function getRequestMethod()
    {
        return Server::get('REQUEST_METHOD');
    }

    /**
     * Is the request HEAD
     *
     * @return bool
     */
    public static function isHead()
    {
        return Server::get('REQUEST_METHOD') == static::REQUEST_METHOD_HEAD;
    }

    /**
     * Is the request GET
     *
     * @return bool
     */
    public static function isGet()
    {
        return Server::get('REQUEST_METHOD') == static::REQUEST_METHOD_GET;
    }

    /**
     * Is the request POST
     *
     * @return bool
     */
    public static function isPost()
    {
        return Server::get('REQUEST_METHOD') == static::REQUEST_METHOD_POST;
    }

    /**
     * Is the request PUT
     *
     * @return bool
     */
    public static function isPut()
    {
        return Server::get('REQUEST_METHOD') == static::REQUEST_METHOD_PUT;
    }

    /**
     * Is the request PATCH
     *
     * @return bool
     */
    public static function isPatch()
    {
        return Server::get('REQUEST_METHOD') == static::REQUEST_METHOD_PATCH;
    }

    /**
     * Is the request DELETE
     *
     * @return bool
     */
    public static function isDelete()
    {
        return Server::get('REQUEST_METHOD') == static::REQUEST_METHOD_DELETE;
    }

    /**
     * Is the request OPTIONS
     *
     * @return bool
     */
    public static function isOptions()
    {
        return Server::get('REQUEST_METHOD') == static::REQUEST_METHOD_OPTIONS;
    }

    /**
     * Is the request TRACE
     *
     * @return bool
     */
    public static function isTrace()
    {
        return Server::get('REQUEST_METHOD') == static::REQUEST_METHOD_TRACE;
    }

    /**
     * Is the request CONNECT
     *
     * @return bool
     */
    public static function isConnect()
    {
        return Server::get('REQUEST_METHOD') == static::REQUEST_METHOD_CONNECT;
    }

    /**
     * Is the request sent through a command line interface
     *
     * @return bool
     */
    public static function isCli()
    {
        return PHP_SAPI == 'cli';
    }

    /**
     * Is the request not sent through a command line interface
     *
     * @return bool
     */
    public static function isNotCli()
    {
        return !static::isCli();
    }

    /**
     * Retrieve the current protocol
     *
     * @param bool $bForUrl
     *
     * @return string
     */
    public static function getProtocol($bForUrl = false)
    {
        return sprintf('%2$s%1$s', ($bForUrl ? '://' : ''), static::isSecure() ? 'https' : 'http');
    }

    /**
     * Determine if the current request uses HTTPS
     *
     * @return bool
     */
    public static function isSecure()
    {
        return (Server::get('HTTPS') && Server::get('HTTPS') != 'off') ||
            static::isForwardedSecure();
    }

    /**
     * Determine if the current forwarded request uses HTTPS
     *
     * @return bool
     */
    public static function isForwardedSecure()
    {
        return (Server::get('HTTP_X_FORWARDED_PROTO') && Server::get('HTTP_X_FORWARDED_PROTO') == 'https') ||
            (Server::get('HTTP_X_FORWARDED_SSL') && Server::get('HTTP_X_FORWARDED_SSL') == 'on');
    }

    /**
     * Set request parameters
     *
     * @param array $aParameters
     */
    public static function setParameters($aParameters)
    {
        static::$rawParameters = $aParameters;
    }

    /**
     * Prepare the set parameters for usage
     *
     */
    protected static function prepareParameters()
    {
        if (!static::$parameters) {
            $aParameters = static::$rawParameters;
            // drop controller segment
            if ($sControllerSegment = static::getControllerSegment()) {
                $aControllerSegments = explode('/', $sControllerSegment);
                foreach ($aControllerSegments as $sControllerSegment) {
                    array_shift($aParameters);
                }
            }

            if (!static::isActionIndex()) {
                // drop action segment
                array_shift($aParameters);
            }

            foreach ($aParameters as $iIndex => $mValue) {
                if (isset(static::$params[$iIndex])) {
                    static::$parameters[static::$params[$iIndex]] = $mValue;
                } else {
                    break;
                }
            }
        }
    }

    /**
     * Set GET key-value pairs
     *
     * @param array $aGet
     *
     * @note only works in CLI mode
     */
    public static function setGet($aGet)
    {
        if (static::isCli()) {
            static::$get = array_merge(static::$get, $aGet);
        }
    }

    /**
     * Set the AMP mode
     *
     * @param bool $bMode
     */
    public static function setAmpMode($bMode)
    {
        static::$amp = $bMode;
    }

    /**
     * Is the request in AMP mode
     *
     * @return bool
     */
    public static function isAmp()
    {
        return static::$amp;
    }

    /**
     * Is the action the index action
     *
     * @return bool
     */
    public static function isActionIndex()
    {
        return static::$actionSegment == static::ACTION_INDEX;
    }
}
