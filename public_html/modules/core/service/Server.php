<?php

class Server
{
    use Initializable;

    /**
     * _SERVER
     *
     * @var array
     */
    protected static $server;

    /**
     * Initialize the server class
     *
     */
    public static function init()
    {
        if (!static::isInitialized()) {
            static::$server = $_SERVER;

            static::initialize();
        }
    }

    /**
     * Retrieve an item
     *
     * @param string $sName
     *
     * @return mixed|null
     */
    public static function get($sName)
    {
        return isset(static::$server[$sName]) ? static::$server[$sName] : null;
    }

    /**
     * Set an item if it does not already exist
     *
     * @param string $sName
     * @param mixed  $mValue
     *
     * @note only works in CLI mode
     */
    public static function set($sName, $mValue)
    {
        if (Request::isCli() && !static::get($sName)) {
            static::$server[$sName] = $mValue;
        }
    }
}