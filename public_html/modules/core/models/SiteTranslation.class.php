<?php

class SiteTranslation extends Model
{

    public  $siteTranslationId = null;
    public  $languageId        = null;
    public  $label             = null;
    public  $text              = null;
    public  $editable          = 1;
    public  $created           = null;
    public  $createdBy         = null;
    public  $modified          = null;
    public  $modifiedBy        = null;
    private $oLanguage         = null;

    function validate()
    {
        if (empty($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->label)) {
            $this->setPropInvalid('label');
        }

        $oDuplicate = SiteTranslationManager::getTranslationByLabel($this->languageId, $this->label);
        if ($oDuplicate && $oDuplicate->siteTranslationId != $this->siteTranslationId) {
            $this->setPropInvalid('siteTranslationLabel');
        }

        if (empty($this->text)) {
            $this->setPropInvalid('text');
        }
    }

    public function getLanguage()
    {
        if ($this->oLanguage === null) {
            $this->oLanguage = LanguageManager::getLanguageById($this->languageId);
        }

        return $this->oLanguage;
    }

    /**
     * check if page is editable
     *
     * @return boolean
     */
    public function isEditable()
    {
        $oCurrentUser = UserManager::getCurrentUser();

        return $this->editable || ($oCurrentUser && $oCurrentUser->isAdmin()); // admin can always edit pages
    }

    /**
     * just returns integer, DO NOT USE FOR IS EDITABLE CHECKING
     * return value of editable
     *
     * @return int
     */
    public function getEditable()
    {
        return $this->editable;
    }

}
