<?php

trait Initializable
{
    /**
     * Keep track of initialization state
     *
     * @var bool
     */
    protected static $initialized = false;

    /**
     * Has the singleton been initialized
     *
     * @return bool
     */
    protected static function isInitialized()
    {
        return static::$initialized;
    }

    /**
     * Set the status to initialized
     */
    protected static function initialize()
    {
        static::$initialized = true;
    }
}