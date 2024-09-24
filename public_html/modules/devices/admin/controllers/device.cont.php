<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

# reset crop settings
Session::clear('aCropSettings');

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('device');
$oPageLayout->sModuleName  = sysTranslations::get('device');

# get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
Session::clear('statusUpdate'); //remove statusupdate, always show once

// handle perPage
if (Request::postVar('setPerPage')) {
    Session::set('devicesPerPage', Request::postVar('perPage'));
}

// handle filter
$aDeviceFilter = Session::get('devicesFilter');
if (Request::postVar('filterDevices')) {
    $aDeviceFilter            = Request::postVar('devicesFilter');
    $aDeviceFilter['showAll'] = true; // manually set showAll to true
    Session::set('devicesFilter', $aDeviceFilter);
}

if (Request::postVar('resetFilter') || empty($aDeviceFilter)) {
    Session::clear('devicesFilter');
    $aDeviceFilter                       = [];
    $aDeviceFilter['q']                  = '';
    $aDeviceFilter['showAll']            = true; // manually set showAll to true
}

# handle add/edit
if (Request::param('ID') == 'bewerken' || Request::param('ID') == 'toevoegen') {

    # set crop referrer for pages module
    Session::set('cropReferrer', ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . Request::param('OtherID'));

    if (Request::param('ID') == 'bewerken' && is_numeric(Request::param('OtherID'))) {
        $oDevice = DeviceManager::getDeviceById(Request::param('OtherID'));
        if (!$oDevice) {
            Router::redirect(ADMIN_FOLDER . "/");
        }
        # is editable?
        if (!$oDevice->isEditable()) {
            Session::set('statusUpdate', sysTranslations::get('device_not_edited')); //save status update into session
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
        }
    } else {
        $oDevice             = new Device();       
    }

    # action = save
    if (Request::postVar("action") == 'save' && CSRFSynchronizerToken::validate()) {
        
        # load data in object
        $oDevice->_load($_POST);

        $aDeviceGroups = [];
        foreach (http_post("deviceGroupIds", []) AS $iDeviceGroupId) {
            $aDeviceGroups[] = new DeviceGroup(['deviceGroupId' => $iDeviceGroupId]);
        }

        # set modules into device
        $oDevice->setDeviceGroups($aDeviceGroups);

        # if object is valid, save
        if ($oDevice->isValid()) {
            DeviceManager::saveDevice($oDevice); //save item
            Session::set('statusUpdate', sysTranslations::get('device_saved')); //save status update into session           

            saveLog(
                ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oDevice->deviceId,
                ' Apparaat opgeslagen #' . $oDevice->deviceId . ' ',
                arrayToReadableText(object_to_array($oDevice))
              );

            if ($_POST["save"]==sysTranslations::get('global_save')) {
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oDevice->deviceId);
            } else {
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
            }
        } else {
            Debug::logError("", "Device module php validate error", __FILE__, __LINE__, "Tried to save Device with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('device_not_saved');
        }
    }

    

    $oPageLayout->sViewPath = getAdminView('devices/device_form', 'devices');

} elseif (Request::param('ID') == 'verwijderen' && is_numeric(Request::param('OtherID'))) {
    if (CSRFSynchronizerToken::validate()) {
        if (is_numeric(Request::param('OtherID'))) {
            $oDevice = DeviceManager::getDeviceById(Request::param('OtherID'));
        }
        if ($oDevice && DeviceManager::deleteDevice($oDevice)) {

            saveLog(
                ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oDevice->deviceId,
                ' Apparaat gewist #' . $oDevice->deviceId . ' ',
                arrayToReadableText(object_to_array($oDevice))
              );


            Session::set('statusUpdate', sysTranslations::get('device_deleted')); //save status update into session
        } else {
            Session::set('statusUpdate', sysTranslations::get('device_not_deleted')); //save status update into session
        }
    }
    Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());

} elseif (Request::param('ID') == 'ajax-setOnline') {
    if (!CSRFSynchronizerToken::validate()) {
        die(json_encode(['success'=>false]));
    }
    $bOnline     = Request::getVar("online") ? Request::getVar("online") : 0; //no value, set offline by default
    $bAjax       = Request::getVar("ajax") ? Request::getVar("ajax") : false; //controller requested by ajax
    $iDeviceId = Request::param('OtherID');
    $oResObj     = new stdClass(); //standard class for json feedback
    # update online for page
    if (is_numeric($iDeviceId)) {
        $oResObj->success    = DeviceManager::updateOnlineByDeviceId($bOnline, $iDeviceId);
        $oResObj->deviceId = $iDeviceId;
        $oResObj->online     = $bOnline;
    }

    if (!$bAjax) {
        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    }
    print json_encode($oResObj);
    die;
} else {

    //$aDeviceFilter[] = '';

    $iPerPage = 9999;
    $iStart = 0;
       
    #display overview
    $sOptionHTML = '';
    foreach (DeviceGroupManager::getAllDeviceGroups() as $oDeviceGroup) {        
        $sOptionHTML .= '<option ' . ((isset($aDeviceFilter['deviceGroupId']) && ($aDeviceFilter['deviceGroupId'] == $oDeviceGroup->deviceGroupId)) ? 'selected' : '') . ' value="' . $oDeviceGroup->deviceGroupId . '">' . $oDeviceGroup->title . '</option>';
    }

    if (isset($aDeviceFilter['deviceGroupId']) && is_numeric($aDeviceFilter['deviceGroupId'])) {
        $aAllDevices = DeviceManager::getDevicesByDeviceGroupId($aDeviceFilter['deviceGroupId']);
    } else {
        $aAllDevices = DeviceManager::getDevicesByFilter($aDeviceFilter, $iPerPage, $iStart, $iFoundRows);
    }
    $oPageLayout->sViewPath = getAdminView('devices/devices_overview', 'devices');
}

# include template
include_once getAdminView('layout');