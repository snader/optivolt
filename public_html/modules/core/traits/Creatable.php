<?php

trait Creatable
{
    /**
     * Create a new instance
     *
     * @param array ...$aArguments
     *
     * @return static
     */
    public static function create(...$aArguments)
    {
        return new static(...$aArguments);
    }
}