<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('modules_management');
$oPageLayout->sModuleName  = sysTranslations::get('modules_management');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

$iMaxLevels = 2;

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oModule = ModuleManager::getModuleById(http_get("param2"));
        if (!$oModule) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oModule                 = new Module ();
        $oModule->parentModuleId = http_get('parentModuleId');
    }

    # action = save
    if (http_post("action") == 'save') {

        # load data in object
        $oModule->_load($_POST);
        
        # if object is valid, save
        if ($oModule->isValid()) {
            ModuleManager::saveModule($oModule); //save module
            $_SESSION['statusUpdate'] = sysTranslations::get('modules_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oModule->moduleId);
        } else {
            Debug::logError("", "Modules module php validate error", __FILE__, __LINE__, "Tried to save Module with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('modules_not_saved');
        }
    }

    $aModuleActions = $oModule->getModuleActions();

    $oPageLayout->sViewPath = getAdminView('modules/module_form');
} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if (is_numeric(http_get("param2"))) {
        $oModule = ModuleManager::getModuleById(http_get("param2"));
    }

    if ($oModule && ModuleManager::deleteModule($oModule)) {
        $_SESSION['statusUpdate'] = sysTranslations::get('modules_deleted'); //save status update into session
    } else {
        $_SESSION['statusUpdate'] = sysTranslations::get('modules_not_deleted'); //save status update into session
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get('param1') == 'ajax-addModuleAction' || http_get('param1') == 'ajax-editModuleAction') {

    if (http_get('param1') == 'ajax-editModuleAction') {
        $oModuleAction = ModuleActionManager::getModuleActionById(http_get('param2'));

        if (!$oModuleAction || !$oModuleAction->isEditable()) {
            die('Module action not found');
        }

        $oModule = $oModuleAction->getModule();
        if (!$oModule) {
            die('Module not found');
        }
    } else {
        $oModuleAction = new ModuleAction();

        $oModule = ModuleManager::getModuleById(http_get('moduleId'));
        if (!$oModule) {
            die('Module not found');
        }

        $oModuleAction->moduleId = $oModule->moduleId;
    }

    if (http_post('action') == 'saveModuleAction') {
        $oModuleAction->_load($_POST);

        if ($oCurrentUser->isSuperAdmin()) {
            $oModuleAction->setEditable(http_post('editable'));
            $oModuleAction->setDeletable(http_post('deletable'));
        }

        if ($oModuleAction->isValid()) {
            ModuleActionManager::saveModuleAction($oModuleAction);
            $_SESSION['statusUpdate'] = sysTranslations::get('moduleActions_saved');
            die('<script>window.location.reload();</script>');
        } else {
            $aErrors = [];
            if (!$oModuleAction->isPropValid('moduleId')) {
                $aErrors['moduleId'] = sysTranslations::get('moduleActions_module_not_found');
            }
            if (!$oModuleAction->isPropValid('displayName')) {
                $aErrors['displayName'] = sysTranslations::get('moduleActions_displayName_tooltip');
            }
            if (!$oModuleAction->isPropValid('name')) {
                $aErrors['name'] = sysTranslations::get('moduleActions_name_tooltip');
            }
        }
    }

    include getAdminView('modules/moduleAction_form');

    die;
} elseif (http_get("param1") == 'deleteModuleAction' && is_numeric(http_get("param2"))) {
    if (is_numeric(http_get("param2"))) {
        $oModuleAction = ModuleActionManager::getModuleActionById(http_get("param2"));
    }

    if ($oModuleAction && $oModuleAction->isDeletable() && ModuleActionManager::deleteModuleAction($oModuleAction)) {
        $_SESSION['statusUpdate'] = sysTranslations::get('moduleActions_deleted'); //save status update into session
    } else {
        $_SESSION['statusUpdate'] = sysTranslations::get('moduleActions_not_deleted'); //save status update into session
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oModuleAction->moduleId);
} elseif (http_get("param1") == 'structuur-wijzigen') {

    if (http_post("action") == 'saveModuleStructure') {

        if (http_post("moduleStructure")) {
            parse_str(http_post("moduleStructure"), $aModuleArray);
        }

        $iModuleOrder = 0;
        # loop trough all modules and save new parentId
        foreach ($aModuleArray['module'] AS $iModuleId => $mParentModuleReference) {

            # get module and save
            $oModule = ModuleManager::getModuleById($iModuleId);

            if (!$oModule) {
                continue;
            }
            if ($mParentModuleReference == 'root') {
                $oModule->parentModuleId = null;
            } elseif (is_numeric($mParentModuleReference)) {
                $oModule->parentModuleId = $mParentModuleReference;
            } else {
                continue;
            }

            $oModule->order = $iModuleOrder; // set the order to the module
            # save module
            if ($oModule->isValid()) {
                ModuleManager::saveModule($oModule);
            }
            $iModuleOrder++;
        }
        $_SESSION['statusUpdate'] = 'Modulestructuur is opgeslagen'; //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    $aAllLevel1Modules      = ModuleManager::getModulesByFilter(['showAll' => true, 'parentModuleId' => -1]);
    $oPageLayout->sViewPath = getAdminView('modules/modules_change_structure');
} else {
    $aAllLevel1Modules      = ModuleManager::getModulesByFilter(['showAll' => true, 'parentModuleId' => -1]);
    $oPageLayout->sViewPath = getAdminView('modules/modules_overview');
}

# include default template
include_once getAdminView('layout');
?>