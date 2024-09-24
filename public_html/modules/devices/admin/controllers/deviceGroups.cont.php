<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('device_groups');
$oPageLayout->sModuleName  = sysTranslations::get('device_groups');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oDeviceGroup = DeviceGroupManager::getDeviceGroupById(http_get("param2"));
        if (empty($oDeviceGroup)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oDeviceGroup = new DeviceGroup();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oDeviceGroup->_load($_POST);

        # if object is valid, save
        if ($oDeviceGroup->isValid()) {
            DeviceGroupManager::saveDeviceGroup($oDeviceGroup); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('device_group_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oDeviceGroup->deviceGroupId);
        } else {
            Debug::logError("", "Device group module php validate error", __FILE__, __LINE__, "Tried to save Device group with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('device_group_not_saved');
        }
    }

    $aClients               = $oDeviceGroup->getClients(true);
    $oPageLayout->sViewPath = getAdminView('deviceGroups/deviceGroup_form', 'devices');
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oDeviceGroup = DeviceGroupManager::getDeviceGroupById(http_get("param2"));
        }

        # trigger error
        if (empty($oDeviceGroup)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('device_group_not_deleted'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
        }

        # get linked devices
        $aLinkedDevices = $oDeviceGroup->getClients();
        if (!empty($aLinkedDevices)) {
            # delete linked devices
            foreach ($aLinkedDevices as $oGroupDevice) {
                DeviceGroupManager::deleteDeviceFromDeviceGroup($oDeviceGroup->deviceGroupId, $oGroupDevice->deviceId);
            }
        }

        if (!empty($oDeviceGroup) && DeviceGroupManager::deleteDeviceGroup($oDeviceGroup)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('device_group_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('device_group_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get("param1") == 'klanten-toevoegen' && is_numeric(http_get("param2"))) {

    # action = save
    if (http_post("action") == 'addMembersToDeviceGroup' && CSRFSynchronizerToken::validate()) {
        $aDeviceIds = http_post('aDeviceIds', []);
        foreach ($aDeviceIds as $iDeviceId) {
            DeviceManager::saveDeviceGroupRelation($iDeviceId, http_get('param2'));
        }
        if (!empty($aDeviceIds)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('device_added'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('device_not_added'); //save status update into session
        }
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/' . http_get('param1') . '/' . http_get('param2'));

    }

    # get all device instances
    $aDevices = DeviceManager::getAllDevices();

    # get unique deviceid's of all devices
    $aDevicesIds = [];
    foreach ($aDevices as $oDevices) {
        $aDevicesIds[] = $oDevices->deviceId;
    }

    # get current group
    $oDeviceGroup = DeviceGroupManager::getDeviceGroupById(http_get("param2"));

    # get clients of group
    $aDevicesOfGroup = $oDeviceGroup->getClients();

    # get unique deviceid's of all devices
    $aDeviceIdsOfGroup = [];
    foreach ($aDevicesOfGroup as $oDeviceOfGroup) {
        $aDeviceIdsOfGroup[] = $oDeviceOfGroup->deviceId;
    }

    # collect objects of devices not on this list
    $aDevicesNotInThisGroup = [];

    # compare device id's to check the differences
    $aDeviceIdsNotInThisGroup = array_diff($aDevicesIds, $aDeviceIdsOfGroup);
    foreach ($aDeviceIdsNotInThisGroup as $iDeviceIdNotInThisGroup) {
        $oDevice                  = DeviceManager::getDeviceById($iDeviceIdNotInThisGroup);
        $aDevicesNotInThisGroup[] = $oDevice;
    }

    # load view
    $oPageLayout->sViewPath = getAdminView('deviceGroups/devicesGroup_addMembers', 'devices');
} elseif (http_get('param1') == 'ajax-checkName') {
    # check if name exists
    $oDeviceGroup = DeviceGroupManager::getDeviceGroupByName(http_get('name'));
    if ($oDeviceGroup && $oDeviceGroup->deviceGroupId != http_get('deviceGroupId')) {
        echo 'false';
    } else {
        echo 'true';
    }
    die;
} # display overview
else {
    $aDeviceGroups        = DeviceGroupManager::getAllDeviceGroups();
    $oPageLayout->sViewPath = getAdminView('deviceGroups/devicesGroup_overview', 'devices');
}

# include template
include_once getAdminView('layout');
