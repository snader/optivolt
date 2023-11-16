<?php

class Language extends Model
{

    public $languageId;
    public $code; // ISO 639-1 language code
    public $nativeName; // native name
    public $rtl; // right to left
    public $code3; // ISO 639-2 language code

    protected $aTranslations = [];

    /**
     * validate object
     */

    public function validate()
    {
        if (empty($this->code)) {
            $this->setPropInvalid('code');
        }
        if (empty($this->nativeName)) {
            $this->setPropInvalid('nativeName');
        }
        if (!is_numeric($this->rtl)) {
            $this->setPropInvalid('rtl');
        }
        if (empty($this->code3)) {
            $this->setPropInvalid('code3');
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

    /**
     * @param string $mList
     *
     * @return \LanguageTranslation|\LanguageTranslation[]
     */
    public function getTranslations($mList = 'auto')
    {
        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $oTranslation = LanguageTranslationManager::getLanguageTranslationByLanguageId($this->languageId, $mList);
                $this->aTranslations[$mList] = $oTranslation ?: null;
            } else {

                switch ($mList) {
                    case 'auto-admin':
                        $oTranslation = LanguageTranslationManager::getLanguageTranslationByLanguageId($this->languageId, Locales::getAdminLocale()->localeId);
                        $this->aTranslations[$mList] = $oTranslation ?: null;
                        break;
                    case 'auto':
                    case 'default':
                        $oTranslation = LanguageTranslationManager::getLanguageTranslationByLanguageId($this->languageId, Locales::locale());
                        $this->aTranslations[$mList] = $oTranslation ?: null;
                        break;
                    default:
                        die('no option');
                        break;
                }
            }
        }

        return $this->aTranslations[$mList];
    }

}
