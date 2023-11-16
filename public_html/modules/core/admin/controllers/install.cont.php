<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

set_time_limit(180);

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('install_installation');
$oPageLayout->sModuleName  = sysTranslations::get('install_installation');

$aSiteControllerRoutes = Router::getAdminRouteManifest();

if (http_get('param1') == 'ajax-getReadme') {

    $sModule = http_get('param2');

    if (!file_exists(SYSTEM_MODULES_FOLDER . '/' . $sModule . '/build/readme.txt')) {
        die('Readme not found');
    }

    echo '<h2 style="margin-bottom: 10px;">Readme.txt for `' . $sModule . '`</h2>';
    echo '<pre style="min-width: 650px;">';
    include_once SYSTEM_MODULES_FOLDER . '/' . $sModule . '/build/readme.txt';
    echo '</pre>';
    die;
} else {

    $aInstalledModules = json_decode(FileSystem::read(DOCUMENT_ROOT . '/init/mods.json'), true);

    $oPageLayout->sViewPath = getAdminView('install/install_dashboard');

    $iErrors   = 0;
    $iWarnings = 0;

    $aSystemLanguages       = SystemLanguageManager::getLanguagesByFilter();
    $aSystemLanguagesByAbbr = [];
    foreach ($aSystemLanguages AS $oSystemLanguage) {
        $aSystemLanguagesByAbbr[$oSystemLanguage->abbr] = $oSystemLanguage->systemLanguageId;
    }

    $aLanguages       = LanguageManager::getLanguagesByFilter(['hasLocale' => true]);
    $aLanguagesByAbbr = [];
    foreach ($aLanguages AS $oLanguage) {
        $aLanguagesByAbbr[$oLanguage->code] = $oLanguage->languageId;
    }

    $oDb = DBConnections::get();

    //If a template installation is triggered, remove unchecked modules
    if (($aKeepModules = Request::postVar('keepModule')) && is_array($aKeepModules) && Request::getVar('install') && (Request::getVar('module') == 'themes' || Request::getVar('module') == 'all-modules')) {
        ThemeHelper::removeModules($aKeepModules);
        ThemeHelper::removeThemes();


    }

// loop trough all install files and include in here
    $oScanDir = scandir(SYSTEM_MODULES_FOLDER);
    if ($oScanDir) {
        foreach ($oScanDir as $sModuleName) {
            // not a file name, continue
            if ($sModuleName == '.' || $sModuleName == '..') {
                continue;
            }
            // exception for moduleGenerator, to be unusable on non-local environments
            if ($sModuleName == 'moduleGenerator' && (!defined('ENVIRONMENT') || in_array(ENVIRONMENT, ['production', 'acceptance'])) ) {
                continue;
            }

            if (!file_exists(SYSTEM_MODULES_FOLDER . '/' . $sModuleName . '/build')) {
                $aLogs[$sModuleName]['warnings'][] = 'No build folder for module `' . $sModuleName . '`';
            } else {
                if (!file_exists(SYSTEM_MODULES_FOLDER . '/' . $sModuleName . '/build/readme.txt')) {
                    $aLogs[$sModuleName]['warnings'][] = 'No readme file for module `' . $sModuleName . '`';
                } else {
                    $aLogs[$sModuleName]['ok'][] = 'Readme file for module `<a class="fancyBoxLink fancybox.ajax" href="' . getCurrentUrlPath() . '/ajax-getReadme/' . $sModuleName . '">' . $sModuleName . '</a>`';
                }
                if (!file_exists(SYSTEM_MODULES_FOLDER . '/' . $sModuleName . '/build/install.php')) {
                    $aLogs[$sModuleName]['warnings'][] = 'No install.php file for module `' . $sModuleName . '`';
                } else {
                    $aCheckRightFolders           = [];
                    $aDependencyModules           = [];
                    $aNeededAdminControllerRoutes = [];
                    $aNeededSiteControllerRoutes  = [];
                    $aNeededModulesForMenu        = [];
                    $aNeededTranslations          = [];
                    $aNeededSiteTranslations      = [];
                    $aNeededSettings              = [];
                    $bInstall                     = http_get('install') && (http_get('module') == $sModuleName || http_get('module') == 'all-modules');
                    include_once SYSTEM_MODULES_FOLDER . '/' . $sModuleName . '/build/install.php';

                    // check rights and existance of folders
                    foreach ($aCheckRightFolders AS $sFolder => $bCreateIfNotExists) {
                        // check if folder exists
                        if (file_exists(DOCUMENT_ROOT . $sFolder)) {
//                        $aLogs[$sModuleName]['ok'][] = 'Folder exists `' . $sFolder . '`';
                            // check if folder is writable
                            if (is_writable(DOCUMENT_ROOT . $sFolder)) {
                                $aLogs[$sModuleName]['ok'][] = 'Folder is writable `' . $sFolder . '`';
                            } else {
                                $aLogs[$sModuleName]['errors'][] = 'Folder is not writable `' . $sFolder . '`';
                            }
                        } elseif ($bInstall && FileSystem::getOrMakeDirectory(DOCUMENT_ROOT . $sFolder, 0777)) {
                            $aLogs[$sModuleName]['ok'][] = 'Folder created `' . $sFolder . '`';
                        } else {
                            $aLogs[$sModuleName]['errors'][] = 'Folder does not exist `' . $sFolder . '`';
                        }
                    }

                    // check existance of modules needed for this module
                    foreach ($aDependencyModules AS $sModule) {
                        if (!moduleExists($sModule)) {
                            $aLogs[$sModuleName]['errors'][] = 'Module does not exist `' . $sModule . '`';
                        }
                    }

                    // check admin controller routes
                    $aNewAdminControllerRoutes = json_decode(FileSystem::read(DOCUMENT_ROOT . '/init/adminRoutes.json'), true);
                    foreach ($aNeededAdminControllerRoutes AS $sPath => $aNeededAdminControllerRoute) {
                        if (!array_key_exists($sPath, $aNewAdminControllerRoutes)) {
                            $aLogs[$sModuleName]['errors'][] = 'Missing admin controller route `' . $sPath . '`';
                            if ($bInstall) {
                                // add new route
                                $aNewAdminControllerRoutes[$sPath] = $aNeededAdminControllerRoute;
                                // save controller routes
                                FileSystem::write(DOCUMENT_ROOT . '/init/adminRoutes.json', json_encode($aNewAdminControllerRoutes, JSON_PRETTY_PRINT));
                            }
                        }
                    }

                    // check site controller routes
                    $aNewSiteControllerRoutes = json_decode(FileSystem::read(DOCUMENT_ROOT . '/init/siteRoutes.json'), true);
                    foreach ($aNeededSiteControllerRoutes AS $sPath => $aNeededSiteControllerRoute) {
                        if (!array_key_exists($sPath, $aNewSiteControllerRoutes)) {
                            $aLogs[$sModuleName]['errors'][] = 'Missing site controller route `' . $sPath . '`';
                            if ($bInstall) {
                                // add new route
                                $aNewSiteControllerRoutes[$sPath] = $aNeededSiteControllerRoute;
                                // save controller routes
                                FileSystem::write(DOCUMENT_ROOT . '/init/siteRoutes.json', json_encode($aNewSiteControllerRoutes, JSON_PRETTY_PRINT));
                            }
                        }
                    }

                    foreach ($aNeededModulesForMenu AS $aModuleData) {
                        // check module existance or create when installing
                        if (!($oNeededModuleForMenu = ModuleManager::getModuleByName($aModuleData['name']))) {
                            $aLogs[$sModuleName]['errors'][] = 'Missing module for menu `' . $aModuleData['name'] . '`';
                            if ($bInstall) {
                                $oNeededModuleForMenu           = new Module();
                                $oNeededModuleForMenu->name     = $aModuleData['name'];
                                $oNeededModuleForMenu->linkName = $aModuleData['linkName'];
                                $oNeededModuleForMenu->icon     = !empty($aModuleData['icon']) ? $aModuleData['icon'] : null;

                                // try to set parent module
                                if (!empty($aModuleData['parentModuleName'])) {
                                    $oParentModule = ModuleManager::getModuleByName($aModuleData['parentModuleName']);
                                    if ($oParentModule) {
                                        $oNeededModuleForMenu->parentModuleId = $oParentModule->moduleId;
                                    }
                                }

                                if ($oNeededModuleForMenu->isValid()) {
                                    ModuleManager::saveModule($oNeededModuleForMenu);
                                } else {
                                    _d($oNeededModuleForMenu->getInvalidProps());
                                    die('Can\'t create module for menu `' . $aModuleData['name'] . '`');
                                }
                            }
                        }

                        if (!empty($oNeededModuleForMenu) && !empty($aModuleData['moduleActions'])) {
                            // check module action existance or create when installing
                            foreach ($aModuleData['moduleActions'] AS $aNeededModuleActionData) {
                                if (!($oNeededModuleAction = ModuleActionManager::getModuleActionByName($aNeededModuleActionData['name']))) {
                                    $aLogs[$sModuleName]['errors'][] = 'Missing module action `' . $aNeededModuleActionData['name'] . '`';
                                    if ($bInstall) {
                                        $oNeededModuleAction              = new ModuleAction();
                                        $oNeededModuleAction->displayName = $aNeededModuleActionData['displayName'];
                                        $oNeededModuleAction->name        = $aNeededModuleActionData['name'];
                                        $oNeededModuleAction->moduleId    = $oNeededModuleForMenu->moduleId;
                                        if ($oNeededModuleAction->isValid()) {
                                            ModuleActionManager::saveModuleAction($oNeededModuleAction);
                                            // get administrators user access group
                                            $oUserAccessGroup = UserAccessGroupManager::getUserAccessGroupByName('administrators');
                                            if ($oUserAccessGroup) {
                                                // get all module actions, add new one and save user access group
                                                $aModuleActions   = $oUserAccessGroup->getModuleActions();
                                                $aModuleActions[] = $oNeededModuleAction;
                                                $oUserAccessGroup->setModuleActions($aModuleActions);
                                                UserAccessGroupManager::saveUserAccessGroup($oUserAccessGroup);
                                            }
                                        } else {
                                            _d($oNeededModuleAction->getInvalidProps());
                                            die('Can\'t create module action `' . $oNeededModuleAction['name'] . '`');
                                        }
                                    }
                                }
                            }
                        }
                    }

                    foreach ($aNeededSettings AS $sNeededSettingData) {
                        if (isset($sNeededSettingData['key']) && strlen($sNeededSettingData['key']) > 0) {
                            if (!($oSetting = SettingManager::getSettingByName($sNeededSettingData['key']))) {
                                $aLogs[$sModuleName]['errors'][] = 'Missing setting `' . ($sNeededSettingData['key']) . '`';
                                if ($bInstall) {
                                    $oSetting       = new Setting();
                                    $oSetting->name = $sNeededSettingData['key'];
                                    if (isset($sNeededSettingData['default']) && strlen($sNeededSettingData['key']) > 0) {
                                        $oSetting->value = $sNeededSettingData['default'];
                                    }
                                    if ($oSetting->isValid()) {
                                        SettingManager::saveSetting($oSetting);
                                    }
                                }
                            }

                            if ($oSetting = SettingManager::getSettingByName($sNeededSettingData['key']) && !$oSetting) {
                                $aLogs[$sModuleName]['errors'][] = 'Please enter the `' . ($sNeededSettingData['key']) . '` in the settings module';
                            }
                        }
                    }

                    foreach ($aNeededTranslations AS $sLanguageAbbr => $aNeededTranslationsData) {
                        // check language existance
                        if (isset($aSystemLanguagesByAbbr[$sLanguageAbbr])) {
                            $iSystemLanguageId = $aSystemLanguagesByAbbr[$sLanguageAbbr];
                        } else {
                            $aLogs[$sModuleName]['warnings'][] = 'Missing systemlanguage `' . $sLanguageAbbr . '`';
                            continue;
                        }

                        foreach ($aNeededTranslationsData AS $aNeededTranslationData) {
                            // check translation existance or create when installing
                            if (!($oNeededSystemTranslation = SystemTranslationManager::getTranslationByLabel($iSystemLanguageId, $aNeededTranslationData['label']))) {
                                $aLogs[$sModuleName]['errors'][] = 'Missing translation `' . $aNeededTranslationData['label'] . '`';
                                if ($bInstall) {
                                    $oNeededSystemTranslation                   = new SystemTranslation();
                                    $oNeededSystemTranslation->label            = $aNeededTranslationData['label'];
                                    $oNeededSystemTranslation->text             = $aNeededTranslationData['text'];
                                    $oNeededSystemTranslation->systemLanguageId = $iSystemLanguageId;
                                    if ($oNeededSystemTranslation->isValid()) {
                                        SystemTranslationManager::saveTranslation($oNeededSystemTranslation);
                                    } else {
                                        _d($oNeededSystemTranslation->getInvalidProps());
                                        die('Can\'t create translation `' . $aNeededTranslationData['label'] . '`');
                                    }
                                }
                            }
                        }
                    }

                    // site translations
                    foreach ($aNeededSiteTranslations as $sLanguageAbbr => $aSiteTranslationsData) {

                        // check language existance
                        if (isset($aLanguagesByAbbr[$sLanguageAbbr])) {
                            $iLanguageId = $aLanguagesByAbbr[$sLanguageAbbr];
                        } else {
                            if (count(LocaleManager::getLocalesByFilter(['showAll' => true])) > 1) {
                                $aLogs[$sModuleName]['warnings'][] = 'Missing site language `' . $sLanguageAbbr . '`';
                            }
                            continue;
                        }

                        foreach ($aSiteTranslationsData as $aSiteTranslationData) {
                            // check translation exisatance or create when installing
                            if (!($oSiteTranslation = SiteTranslationManager::getTranslationByLabel($iLanguageId, $aSiteTranslationData['label']))) {
                                $aLogs[$sModuleName]['errors'][] = 'Missing site translation `' . $aSiteTranslationData['label'] . '` for language `' . _e(strtoupper(LanguageManager::getLanguageById($iLanguageId)->code)) . '`';
                                if ($bInstall) {
                                    $oSiteTranslation             = new SiteTranslation();
                                    $oSiteTranslation->languageId = $iLanguageId;
                                    $oSiteTranslation->label      = $aSiteTranslationData['label'];
                                    $oSiteTranslation->text       = $aSiteTranslationData['text'];
                                    $oSiteTranslation->editable   = $aSiteTranslationData['editable'];
                                    if ($oSiteTranslation->isValid()) {
                                        SiteTranslationManager::saveTranslation($oSiteTranslation);
                                    } else {
                                        _d($oSiteTranslation->getInvalidProps());
                                        die('Can\'t create site translation `' . $aSiteTranslationData['label'] . '`');
                                    }
                                }
                            }
                        }
                    }

                    SiteTranslations::reset();
                    if (!moduleExists($sModuleName)) {
                        $aLogs[$sModuleName]['errors'][] = 'Module not installed `' . $sModuleName . '`';
                        if ($bInstall) {
                            if ($bInstall) {
                                // add new route
                                $aInstalledModules[$sModuleName] = [];
                                // save controller routes
                                FileSystem::write(DOCUMENT_ROOT . '/init/mods.json', json_encode($aInstalledModules, JSON_PRETTY_PRINT));
                            }
                        }
                    }
                }

                // no errors, add installed file
                if (!empty($aLogs[$sModuleName]['errors'])) {
                    $aLogs[$sModuleName]['errors'][] = 'Not fully installed <a href="?install=true&module=' . $sModuleName . '">[Install]</a>';
                } else {
                    $aLogs[$sModuleName]['ok'][] = 'Install OK';
                }
            }
        }
    }
}

// refresh page after install
if (http_get('install')) {
    sysTranslations::reset();
    http_redirect(getCurrentUrlPath());
}

foreach ($aLogs AS $sModule => $aErrors) {
    foreach ($aErrors AS $sType => $aMsgs) {
        if ($sType == 'errors') {
            $iErrors += count($aMsgs);
        } elseif ($sType == 'warnings') {
            $iWarnings += count($aMsgs);
        }
    }
}

include_once getAdminView('layout');
?>
