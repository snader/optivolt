<?php

class CountryAdminController extends AdminController
{
    protected static $allowed_actions = [
        'save',
    ];

    public function init()
    {
        parent::init();

        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        $oPageLayout->sModuleName  = sysTranslations::get('core_countries');
        $oPageLayout->sWindowTitle = sysTranslations::get('core_countries');
    }

    public function index()
    {
        $this->getRenderEngine()
            ->setVariables(
                [
                    'aCountries' => CountryManager::getCountriesByFilter(['showAll' => true]),
                ]
            )->getLayout()->sViewPath = getAdminView('country/index');
    }

    public function save()
    {
        foreach(Request::postVar('translation') as $iCountryTranslationId => $sName) {
            if ($oTranslation = CountryTranslationManager::getCountryTranslationByCountryTranslationId($iCountryTranslationId)) {
                $oTranslation->name = $sName;

                CountryTranslationManager::saveCountryTranslation($oTranslation);
            }
        }

        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    }
}