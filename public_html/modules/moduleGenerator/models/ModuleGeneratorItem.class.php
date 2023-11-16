<?php

class ModuleGeneratorItem extends Model
{

    public $hasFiles      = 1;
    public $hasImages     = 1;
    public $hasVideos     = 1;
    public $hasLinks      = 1;
    public $hasCategories = 0;
    public $hasUrls       = 0;

    // module naming
    public $moduleFolderName                 = null;
    public $classFileName                    = null;
    public $controllerFileName               = null;
    public $singleSystemFileName             = null;
    public $multipleSystemFileName           = null;
    public $moduleDescription                = null;
    public $defaultLocaleTranslationItem     = null;
    public $defaultLocaleTranslationItems    = null;
    public $notDefaultLocaleTranslationItem  = null;
    public $notDefaultLocaleTranslationItems = null;

    // FE routing
    public $controllerRoute                     = null;
    public $pageSystemName                      = null;
    public $defaultLocalePageTitle              = null;
    public $notDefaultLocalePageTitle           = null;
    public $defaultLocalePageControllerRoute    = null;
    public $notDefaultLocalePageControllerRoute = null;

    // DB settings
    public $idName                  = null;
    public $databaseAlias           = null;
    public $relationTableNamePrefix = null;

    // fontawesome
    public $fontawesomeIcon = null;

    /**
     * validate object
     */
    public function validate()
    {

        if (!is_numeric($this->hasFiles)) {
            $this->setPropInvalid('hasFiles');
        }
        if (!is_numeric($this->hasImages)) {
            $this->setPropInvalid('hasImages');
        }
        if (!is_numeric($this->hasLinks)) {
            $this->setPropInvalid('hasLinks');
        }
        if (!is_numeric($this->hasCategories)) {
            $this->setPropInvalid('hasCategories');
        }
        if (!is_numeric($this->hasUrls)) {
            $this->setPropInvalid('hasUrls');
        }
        if (empty($this->moduleFolderName)) {
            $this->setPropInvalid('moduleFolderName');
        }
        if (empty($this->classFileName)) {
            $this->setPropInvalid('classFileName');
        }
        if (empty($this->controllerFileName)) {
            $this->setPropInvalid('controllerFileName');
        }
        if (empty($this->singleSystemFileName)) {
            $this->setPropInvalid('singleSystemFileName');
        }
        if (empty($this->multipleSystemFileName)) {
            $this->setPropInvalid('multipleSystemFileName');
        }
        if (empty($this->moduleDescription)) {
            $this->setPropInvalid('moduleDescription');
        }
        if (empty($this->defaultLocaleTranslationItem)) {
            $this->setPropInvalid('defaultLocaleTranslationItem');
        }
        if (empty($this->defaultLocaleTranslationItems)) {
            $this->setPropInvalid('defaultLocaleTranslationItems');
        }
        if (empty($this->notDefaultLocaleTranslationItem)) {
            $this->setPropInvalid('notDefaultLocaleTranslationItem');
        }
        if (empty($this->notDefaultLocaleTranslationItems)) {
            $this->setPropInvalid('notDefaultLocaleTranslationItems');
        }
        if (empty($this->relationTableNamePrefix)) {
            $this->setPropInvalid('relationTableNamePrefix');
        }
        if (empty($this->fontawesomeIcon)) {
            $this->setPropInvalid('fontawesomeIcon');
        }
        if ($this->hasUrls) {
            if (empty($this->pageSystemName)) {
                $this->setPropInvalid('pageSystemName');
            }
            if (empty($this->defaultLocalePageTitle)) {
                $this->setPropInvalid('defaultLocalePageTitle');
            }
            if (empty($this->notDefaultLocalePageTitle)) {
                $this->setPropInvalid('notDefaultLocalePageTitle');
            }
            if (empty($this->defaultLocalePageControllerRoute)) {
                $this->setPropInvalid('defaultLocalePageControllerRoute');
            }
            if (empty($this->notDefaultLocalePageControllerRoute)) {
                $this->setPropInvalid('notDefaultLocalePageControllerRoute');
            }
        }

    }

}