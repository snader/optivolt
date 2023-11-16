<?php

class Validator
{
    public static function isEmail($sEmail)
    {
        return preg_match('/^[a-zA-Z0-9._%+-]+@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}+$/', $sEmail);
    }
}