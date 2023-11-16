<?php

class LanguageAdminController extends AdminController
{
    protected static $allowed_actions = [
        'save',
    ];

    public function init()
    {
        parent::init();

        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        $oPageLayout->sModuleName  = sysTranslations::get('core_languages');
        $oPageLayout->sWindowTitle = sysTranslations::get('core_languages');
    }

    public function index()
    {
        $this->getRenderEngine()
            ->setVariables(
                [
                    'aLanguages' => LanguageManager::getLanguagesByFilter(['showAll' => true]),
                ]
            )->getLayout()->sViewPath = getAdminView('language/index');
    }

    public function save()
    {
        foreach(Request::postVar('translation') as $iLanguageTranslationId => $sName) {
            if ($oTranslation = LanguageTranslationManager::getLanguageTranslationByLanguageTranslationId($iLanguageTranslationId)) {
                $oTranslation->name = $sName;

                LanguageTranslationManager::saveLanguageTranslation($oTranslation);
            }
        }

        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    }
}