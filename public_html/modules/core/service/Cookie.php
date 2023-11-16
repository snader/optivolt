<?php

class Cookie
{
    use Initializable;

    /**
     * _COOKIE
     *
     * @var string[]
     */
    protected static $jar;

    /**
     * Initialize the cookie jar
     *
     */
    public static function init()
    {
        if (!static::isInitialized()) {
            static::$jar = $_COOKIE;
            static::initialize();
        }
    }

    /**
     * Set a cookie
     *
     * @param string $sName
     * @param string $sValue
     * @param int    $iExpire
     * @param string $sPath
     * @param string $sDomain
     * @param bool   $bSecure
     * @param bool   $bHttpOnly
     *
     * @return bool
     */
    public static function set($sName, $sValue = '', $iExpire = 0, $sPath = '/', $sDomain = '', $bSecure = false, $bHttpOnly = false)
    {
        return setcookie($sName, $sValue, $iExpire, $sPath, $sDomain, $bSecure, $bHttpOnly);
    }

    /**
     * Retrieve a cookie value
     *
     * @param string $sName
     *
     * @return mixed|null
     */
    public static function get($sName)
    {
        return isset(static::$jar[$sName]) ? static::$jar[$sName] : null;
    }

    /**
     * Retrieve all cookies
     *
     * @return string[]
     */
    public static function getAll()
    {
        return static::$jar;
    }

    /**
     * Clear a cookie (expires the cookie without a value)
     *
     * @param string $sName
     *
     * @return bool
     */
    public static function clear($sName)
    {
        return static::set($sName, '', time()-1);
    }

    /**
     * Clears all known cookies
     *
     */
    public static function destroy()
    {
        foreach(static::$jar as $sCookie => $sValue) {
            static::clear($sCookie);
        }
    }
}