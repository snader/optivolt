<?php

// Models and managers used by this class

class Redirect extends Model
{

    const TYPE_SPECIFIC   = 1;
    const TYPE_EXPRESSION = 2;

    public $redirectId = null;
    public $type;
    public $pattern;
    public $newUrl;
    public $online     = 1;
    public $created    = null;
    public $modified   = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->pattern) || $this->existsInBlacklist($this->pattern) || strlen($this->pattern) > 255) {
            $this->setPropInvalid('pattern');
        }
        if (empty($this->newUrl) || strlen($this->newUrl) > 255) {
            $this->setPropInvalid('newUrl');
        }
    }

    /**
     * Check if pattern is blacklisted
     *
     * @param $sPattern
     *
     * @return bool
     */
    private function existsInBlacklist($sPattern)
    {
        $aBlacklist = [
            '^/?admin($|/)',
        ];
        foreach ($aBlacklist as $sBlacklist) {
            if (preg_match('#' . $sBlacklist . '#', $sPattern)) {
                return true;
            }

            return false;
        }
    }
}
