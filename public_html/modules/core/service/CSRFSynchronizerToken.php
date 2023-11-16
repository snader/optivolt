<?php

class CSRFSynchronizerToken
{
    /**
     * Constant Session Element Name
     */
    public const SESSION = 'csrftoken';

    /**
     * Constant Field Name
     */
    public const FIELD = 'SecurityID';

    /**
     * CSRF Token
     *
     * @var array
     */
    protected static $token = [];

    /**
     * Retrieve the current CSRF token
     *
     * @param bool $bForce
     *
     * @return string
     * @throws \Exception
     */
    public static function get($bForce = false)
    {
        if (!$bForce) {
            if (isset(static::$token[static::SESSION])) {
                return static::$token[static::SESSION];
            }
            if (Session::get(static::SESSION)) {
                return static::$token[static::SESSION] = Session::get(static::SESSION);
            }
        }

        return static::generate();
    }

    /**
     * Generate a new CSRF field with a fresh CSRF token
     *
     * @return string
     * @throws \Exception
     */
    public static function field()
    {
        return sprintf(
            '<input type="hidden" name="%1$s" value="%2$s" />',
            static::FIELD,
            static::get()
        );
    }

    /**
     * Generate a new CSRF query string parameter/value
     *
     * @return string
     * @throws \Exception
     */
    public static function query()
    {
        return sprintf(
            '%1$s=%2$s',
            static::FIELD,
            static::get()
        );
    }

    /**
     * Validate the posted CSRF token versus the stored CSRF token
     *
     * @return bool
     * @throws \Exception
     */
    public static function validate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return Request::postVar(static::FIELD) == static::get();
        }

        return Request::getVar(static::FIELD) == static::get();
    }

    /**
     * Generate a new CSRF token, and store it
     *
     * @return string
     * @throws \Exception
     */
    protected static function generate()
    {
        Session::set(static::SESSION, static::$token[static::SESSION] = hash('sha256', random_bytes(32)));

        return static::$token[static::SESSION];
    }
}