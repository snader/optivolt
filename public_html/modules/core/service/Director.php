<?php

class Director
{
    /**
     * join slugs to form a path
     *
     * @param string[] ...$aSlugs
     *
     * @return string
     */
    public static function join(...$aSlugs)
    {
        return sprintf('/%1$s', implode('/', $aSlugs));
    }

    /**
     * retrieve the base url suffixed with the given slugs
     *
     * @param string[] ...$aSlugs
     *
     * @return string
     */
    public static function getBaseURL(...$aSlugs)
    {
        return CLIENT_HTTP_URL . static::join(...$aSlugs);
    }
}