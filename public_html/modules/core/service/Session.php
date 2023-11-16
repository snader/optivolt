<?php

class Session
{
    /**
     * Keep track of session start
     *
     * @var bool
     */
    private static $started = false;

    /**
     * Start the session
     *
     * @param string|null $sId
     */
    public static function start($sId = null)
    {
        // Session started outside of the class...
        if (!(session_status() == PHP_SESSION_NONE)) {
            static::$started = true;
        }

        if (!static::$started) {
            if ($sId) {
                static::setId($sId);
            }

            if (Request::isSecure() || Environment::isAcceptance() || Environment::isProduction()) {
                ini_set('session.cookie_secure', 1);
                ini_set('session.cookie_httponly', 1); // from 5.2.0
                ini_set('session.cookie_samesite', 'Lax'); // from 7.3.0
            }

            session_start();
            static::$started = true;
        }
    }

    /**
     * Set the session id
     *
     * @param string $sId
     */
    public static function setId($sId)
    {
        session_id($sId);
    }

    /**
     * Destroy the session
     *
     */
    public static function destroy()
    {
        static::start();

        session_destroy();
        static::$started = false;
    }

    /**
     * Retrieve a value from the session
     *
     * @param string $sName
     * @param mixed  $mDefault
     *
     * @return null
     */
    public static function get($sName, $mDefault = null)
    {
        static::start();

        if (isset($_SESSION[$sName])) {
            return $_SESSION[$sName];
        }

        return $mDefault;
    }

    /**
     * Set a value in the session
     *
     * @param string $sName
     * @param mixed  $mValue
     */
    public static function set($sName, $mValue)
    {
        static::start();

        $_SESSION[$sName] = $mValue;
    }

    /**
     * Clear a value from the session
     *
     * @param string $sName
     */
    public static function clear($sName)
    {
        static::start();

        unset($_SESSION[$sName]);
    }

    /**
     * Retrieve the entire session
     *
     * @return array
     */
    public static function getAll()
    {
        static::start();

        return $_SESSION;
    }

    /**
     * Set of Retrieve a flash message
     *
     * @param string $sName
     * @param mixed  $mValue
     *
     * @return mixed
     */
    public static function flash($sName, $mValue = null)
    {
        static::start();

        if ($mValue) {
            static::set($sName, $mValue);

            return null;
        }

        $mValue = static::get($sName);
        static::clear($sName);

        return $mValue;
    }
}
