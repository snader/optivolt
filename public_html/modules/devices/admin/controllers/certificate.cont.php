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
$oPageLayout->sWindowTitle = sysTranslations::get('certificate');
$oPageLayout->sModuleName  = sysTranslations::get('certificate');

# get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
Session::clear('statusUpdate'); //remove statusupdate, always show once


# handle add/edit
if (Request::param('ID') == 'bewerken' || (Request::param('ID') == 'toevoegen' && is_numeric(http_get('deviceId')))) {

    # set crop referrer for pages module
    Session::set('cropReferrer', ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . Request::param('OtherID'));

    if (Request::param('ID') == 'bewerken' && is_numeric(Request::param('OtherID'))) {
        $oCertificate = CertificateManager::getCertificateById(Request::param('OtherID'));
        if (!$oCertificate) {
            Router::redirect(ADMIN_FOLDER . "/");
        }
        # is editable?
        if (!$oCertificate->isEditable()) {
            Session::set('statusUpdate', sysTranslations::get('certificate_not_edited')); //save status update into session
            Router::redirect(ADMIN_FOLDER . '/devices');
        }

        $oDevice = DeviceManager::getDeviceById($oCertificate->deviceId);

    } else {

        $oDevice = DeviceManager::getDeviceById(http_get('deviceId'));
        if (!empty($oDevice)) {
            $oCertificate             = new Certificate();   
            $oCertificate->userId     = UserManager::getCurrentUser()->userId;    
            $oCertificate->deviceId   = $oDevice->deviceId;
        } else {
            Router::redirect(ADMIN_FOLDER . '/devices');
        } 

    }

    # action = save
    if (Request::postVar("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oCertificate->_load($_POST);

        # if object is valid, save
        if ($oCertificate->isValid()) {
            CertificateManager::saveCertificate($oCertificate); //save item
            Session::set('statusUpdate', sysTranslations::get('certificate_saved')); //save status update into session
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oCertificate->certificateId);
        } else {
            Debug::logError("", "Certificate module php validate error", __FILE__, __LINE__, "Tried to save Certificate with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('certificate_not_saved');
        }
    }

    
    $oPageLayout->sViewPath = getAdminView('devices/certificate_form', 'devices');

} elseif (Request::param('ID') == 'verwijderen' && is_numeric(Request::param('OtherID'))) {
    if (CSRFSynchronizerToken::validate()) {
        if (is_numeric(Request::param('OtherID'))) {
            $oCertificate = CertificateManager::getCertificateById(Request::param('OtherID'));
        }
        if ($oCertificate && CertificateManager::deleteCertificate($oCertificate)) {
            Session::set('statusUpdate', sysTranslations::get('certificate_deleted')); //save status update into session
        } else {
            Session::set('statusUpdate', sysTranslations::get('certificate_not_deleted')); //save status update into session
        }
    }
    Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());

}  else {

    Router::redirect(ADMIN_FOLDER . '/devices');
}

# include template
include_once getAdminView('layout');