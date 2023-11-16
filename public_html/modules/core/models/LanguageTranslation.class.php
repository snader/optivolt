<?php

class LanguageTranslation extends Model
{

    public $languageTranslationId;
    public $languageId;
    public $localeId;
    public $name; //name of the languageTranslation

    /**
     * validate object
     */

    public function validate()
    {
        if (empty($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->localeId)) {
            $this->setPropInvalid('localeId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
    }

    /**
     * check if item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {
        return false;
    }

    /**
     * check if item is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        return false;
    }

}
