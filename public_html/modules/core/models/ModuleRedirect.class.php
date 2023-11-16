<?php

class ModuleRedirect extends Model
{

    public  $id           = null;
    public  $languageId;
    public  $vacancyId    = null;
    public  $urlPath; //browser window title
    public  $created; //meta tag keywords
    private $sRedirectUrl = null;

    /**
     * validate object
     * 1 module id must be filled, so change the validate function below when adding a module ( moduleId1 && moduleId2 etc )
     */

    public function validate()
    {
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (!is_numeric($this->vacancyId)) {
            $this->setPropInvalid('vacancyId');
        }
        if (empty($this->urlPath)) {
            $this->setPropInvalid('urlPath');
        }
    }

    public function getRedirectUrl()
    {
        if ($this->sRedirectUrl === null) {
            if (is_numeric($this->vacancyId) && moduleExists('vacancies')) {
                $oVacancy           = VacancyManager::getVacancyById($this->vacancyId);
                $this->sRedirectUrl = $oVacancy->getBaseUrlPath();
            }
        }

        return $this->sRedirectUrl;
    }

}

?>