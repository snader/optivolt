<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('userAccessGroup_management');
$oPageLayout->sModuleName  = sysTranslations::get('userAccessGroup_management');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oUserAccessGroup = UserAccessGroupManager::getUserAccessGroupById(http_get("param2"));
        if (!$oUserAccessGroup || !$oUserAccessGroup->isEditable()) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oUserAccessGroup = new UserAccessGroup ();
    }

    # action = save
    if (http_post("action") == 'save') {

        # load data in object
        $oUserAccessGroup->_load($_POST);

        if ($oCurrentUser->isSuperAdmin()) {
            $oUserAccessGroup->setEditable(http_post('editable', 0));
            $oUserAccessGroup->setDeletable(http_post('deletable', 0));
        }

        # default empty array
        $aModuleActions = [];

        # foreach moduleAction id, add moduleAction object to temporary array for filling user object later
        foreach (http_post("moduleActionIds", []) AS $iModuleActionId) {
            $aModuleActions[] = new ModuleAction(['moduleActionId' => $iModuleActionId]);
        }

        # set modulesAction into user
        $oUserAccessGroup->setModuleActions($aModuleActions);

        # if object is valid, save
        if ($oUserAccessGroup->isValid()) {
            UserAccessGroupManager::saveUserAccessGroup($oUserAccessGroup); //save userAccessGroup
            $_SESSION['statusUpdate'] = sysTranslations::get('userAccessGroup_status_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oUserAccessGroup->userAccessGroupId);
        } else {

            Debug::logError("", "UserAccessGroup module php validate error", __FILE__, __LINE__, "Tried to save userAccessGroup with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('userAccessGroup_status_not_saved');
        }
    }

    $aFilter            = [];
    $aFilter['showAll'] = 1;
    $aFilter['active']  = 1;

    // aside admin can see all modules, others only the ones they have rights for
    if (!$oCurrentUser->isSuperAdmin()) {
        $aFilter['userAccessGroupId'] = $oCurrentUser->userAccessGroupId;
    }

    # get all modules for rights
    $aModules = ModuleManager::getModulesByFilter($aFilter);

    $oPageLayout->sViewPath = getAdminView('/userAccessGroups/userAccessGroup_form');
} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if (is_numeric(http_get("param2"))) {
        $oUserAccessGroup = UserAccessGroupManager::getUserAccessGroupById(http_get("param2"));
    }

    if ($oUserAccessGroup && UserAccessGroupManager::deleteUserAccessGroup($oUserAccessGroup)) {
        $_SESSION['statusUpdate'] = sysTranslations::get('userAccessGroup_status_deleted'); //save status update into session
    } else {
        $_SESSION['statusUpdate'] = sysTranslations::get('userAccessGroup_status_not_deleted'); //save status update into session
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get('param1') == 'ajax-checkName') {

    # check if name exists
    $oUserAccessGroup = UserAccessGroupManager::getUserAccessGroupByName(http_get('name'));
    if ($oUserAccessGroup && $oUserAccessGroup->userAccessGroupId != http_get('userAccessGroupId')) {
        echo 'false';
    } else {
        echo 'true';
    }
    die;
} else {
    $aUserAccessGroups      = UserAccessGroupManager::getUserAccessGroupsByFilter();
    $oPageLayout->sViewPath = getAdminView('/userAccessGroups/userAccessGroups_overview');
}

# include default template
include_once getAdminView('layout');
?>