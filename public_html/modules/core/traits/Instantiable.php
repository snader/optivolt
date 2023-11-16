<?php

trait Instantiable
{
    use Creatable;

    /**
     * Our current instance
     *
     * @var static
     */
    protected static $instance;

    /**
     * Retrieve the instance of the current class
     *
     * @return static
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = static::create();
        }

        return static::$instance;
    }
}