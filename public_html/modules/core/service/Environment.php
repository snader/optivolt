<?php

class Environment
{
    /**
     * Constants environment
     */
    public const PRODUCTION  = 'production';
    public const ACCEPTANCE  = 'acceptance';
    public const STAGING     = self::ACCEPTANCE;
    public const DEVELOPMENT = 'development';
    public const LOCAL       = 'local';

    /**
     * Constant cookie name
     */
    protected const COOKIE = 'neo';

    /**
     * Constant token name
     */
    protected const TOKEN = 'token';

    /**
     * Retrieve the current environment
     *
     * @return string
     */
    public static function get()
    {
        if (defined('ENVIRONMENT')) {
            return ENVIRONMENT;
        } else {
            die('No ENVIRONMENT set!!');
        }
    }

    /**
     * Is the environment production
     *
     * @return bool
     */
    public static function isProduction()
    {
        return static::get() == static::PRODUCTION;
    }

    /**
     * Is the environment acceptance
     *
     * @return bool
     */
    public static function isAcceptance()
    {
        return static::get() == static::ACCEPTANCE;
    }

    /**
     * Alias for isAcceptance
     *
     * @return bool
     */
    public static function isStaging()
    {
        return static::isAcceptance();
    }

    /**
     * Is the environment development
     *
     * @return bool
     */
    public static function isDevelopment()
    {
        return static::get() == static::DEVELOPMENT;
    }

    /**
     * Is the environment local
     *
     * @return bool
     */
    public static function isLocal()
    {
        return !(
            static::isProduction() ||
            static::isAcceptance() ||
            static::isDevelopment()
        );
    }

    /**
     * Is access security enabled
     *
     * @return bool
     */
    public static function isSecurityEnabled()
    {
        return Settings::get('security') == 1 && !isDeveloper() && !Environment::isLocal() && !((Cookie::get(static::COOKIE) ?: Request::getVar(static::TOKEN)) == Settings::get('security-bypass'));
    }

}
