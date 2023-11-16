<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout                     = new PageLayout();
$oPageLayout->sWindowTitle       = sysTranslations::get('templateGroups_management');
$oPageLayout->sTemplateGroupName = sysTranslations::get('templateGroups_management');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oTemplateGroup = TemplateGroupManager::getTemplateGroupById(http_get("param2"));
        if (!$oTemplateGroup) {
            http_redirect(ADMIN_FOLDER . "/");
        }

    } else {
        $oTemplateGroup = new TemplateGroup ();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oTemplateGroup->_load($_POST);

        # if object is valid, save
        if ($oTemplateGroup->isValid()) {
            TemplateGroupManager::saveTemplateGroup($oTemplateGroup); //save templateGroup
            $_SESSION['statusUpdate'] = sysTranslations::get('templateGroups_status_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oTemplateGroup->templateGroupId);
        } else {
            Debug::logError("", "TemplateGroups templateGroup php validate error", __FILE__, __LINE__, "Tried to save TemplateGroup with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('global_field_not_completed');
        }
    }

    $oPageLayout->sViewPath = getAdminView('templateGroup_form', 'templates');
} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oTemplateGroup = TemplateGroupManager::getTemplateGroupById(http_get("param2"));
        }

        if ($oTemplateGroup && $oTemplateGroup->isDeletable() && TemplateGroupManager::deleteTemplateGroup($oTemplateGroup)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('templateGroups_status_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('templateGroups_status_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get('param1') == 'ajax-checkName') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    # check if name exists
    $oTemplateGroup = TemplateGroupManager::getTemplateGroupByName(http_get('name'));
    if ($oTemplateGroup && $oTemplateGroup->templateGroupId != http_get('templateGroupId')) {
        echo 'false';
    } else {
        echo 'true';
    }
    die;
} else {
    $aTemplateGroups        = TemplateGroupManager::getTemplateGroupsByFilter();
    $oPageLayout->sViewPath = getAdminView('templateGroups_overview', 'templates');
}

# include default templateGroup
include_once getAdminView('layout');
?>