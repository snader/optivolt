<?php

class SystemLanguage extends Model
{

    const default_languageId = 1;

    public $systemLanguageId;
    public $abbr;
    public $name;
    public $languageId; // site language id which the system language is mapped to

    public function validate()
    {

    }

}

?>