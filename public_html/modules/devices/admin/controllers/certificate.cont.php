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
            $oCertificate->created    = date('y-m-d', time());
        } else {
            Router::redirect(ADMIN_FOLDER . '/devices');
        } 

    }

    # action = save
    if (Request::postVar("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oCertificate->_load($_POST);
        if (!empty($oCertificate->nextcheck)) {
            $oCertificate->nextcheck = date('Y-m-d', strtotime($oCertificate->nextcheck));
        }
        if (!empty($oCertificate->created)) {
            $oCertificate->created = date('Y-m-d', strtotime($oCertificate->created));
        } else {
            $oCertificate->created = date('Y-m-d', time());
        }

        # if object is valid, save
        if ($oCertificate->isValid()) {
            CertificateManager::saveCertificate($oCertificate); //save item
            Session::set('statusUpdate', sysTranslations::get('certificate_saved')); //save status update into session

            saveLog(
                ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oCertificate->certificateId,
                ' Certificaat opgeslagen #' . $oCertificate->certificateId . ' ',
                arrayToReadableText(object_to_array($oCertificate))
              );

            if ($_POST["save"]==sysTranslations::get('global_save')) {
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oCertificate->certificateId);
            } elseif (substr_count($_POST["save"], 'PDF')) {

                $oDevice = DeviceManager::getDeviceById($oCertificate->deviceId);
                $sFilename = $oDevice->name . ' - ' . $oDevice->brand . ' - ' . Usermanager::getUserById($oCertificate->userId)->name . ' - ' . date('d-m-Y', strtotime($oCertificate->created));

                $oCertificate->getPdf()->Output('Inventarisation - ' . $sFilename . '.pdf', 'D'); 
            } else {
                Router::redirect(ADMIN_FOLDER . '/devices/bewerken/' . $oCertificate->deviceId);
            }


            
        } else {
            Debug::logError("", "Certificate module php validate error", __FILE__, __LINE__, "Tried to save Certificate with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate["text"] = sysTranslations::get('certificate_not_saved');
            $oPageLayout->sStatusUpdate["status"] = "warning";
        }
    }

// action saveFile
if (http_post("action") == 'saveFile') {
    if (CSRFSynchronizerToken::validate()) {

        $bCheckMime = true;

        // for upload from MFUpload
        if (http_post("MFUpload")) {
            $oResObj          = new stdClass();
            $oResObj->success = false;
            $bCheckMime       = true;
        }

        // upload file or return error
        $oUpload = new Upload($_FILES['file'], Certificate::FILES_PATH . '/', (http_post('file_title') != '' ? http_post('file_title') : null), null, $bCheckMime);

        // save file to database on success
        if ($oUpload->bSuccess === true) {

            // make File object and save
            $oFile           = new File();
            $oFile->name     = $oUpload->sNewFileBaseName;
            $oFile->mimeType = $oUpload->sMimeType;
            $oFile->size     = $oUpload->iSize;
            $oFile->link     = $oUpload->sNewFilePath;
            $oFile->title    = http_post('title') == '' ? $oUpload->sFileName : http_post('title');

            // save file
            FileManager::saveFile($oFile);

            // save page file relation
            CertificateManager::saveCertificateFileRelation($oCertificate->certificateId, $oFile->mediaId);

            // for MFUpload
            if (http_post("MFUpload")) {
                $oResObj->success = true;
                // add some extra values
                $oFile->isOnlineChangeable = $oFile->isOnlineChangeable();
                $oFile->isEditable         = $oFile->isEditable();
                $oFile->isDeletable        = $oFile->isDeletable();
                $oFile->extension          = $oFile->getExtension();

                $oResObj->file = $oFile; // for adding file to list
                print json_encode($oResObj);
                die;
            }

            // back to edit
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCertificate->certificateId);
        } else {

            // for MFUpload
            if (http_post("MFUpload")) {
                $oResObj->errorMsg = $oUpload->getErrorMessage();
                print json_encode($oResObj);
                die;
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('global_file_not_uploaded') . $oUpload->getErrorMessage(); //error uploading file
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCertificate->certificateId);
        }
    }

    if (http_post('MFUpload')) {
        die(json_encode(['success'=>false]));
    }
}


    // set settings for file management
    $oFileManagerHTML                      = new FileManagerHTML();
    $oFileManagerHTML->aFiles              = $oCertificate->getFiles('all');
    $oFileManagerHTML->sUploadUrl          = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCertificate->certificateId;
    $oFileManagerHTML->bMultipleFileUpload = false;
    $oFileManagerHTML->bTitleRequired      = false;

    
    $oPageLayout->sViewPath = getAdminView('devices/certificate_form', 'devices');

} elseif (Request::param('ID') == 'verwijderen' && is_numeric(Request::param('OtherID'))) {




 
        if (is_numeric(Request::param('OtherID'))) {
            $oCertificate = CertificateManager::getCertificateById(Request::param('OtherID'));
        }
        if ($oCertificate && CertificateManager::deleteCertificate($oCertificate)) {

            saveLog(
                ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oCertificate->certificateId,
                ' Certificaat gewist #' . $oCertificate->certificateId . ' ',
                arrayToReadableText(object_to_array($oCertificate))
              );


            Session::set('statusUpdate', sysTranslations::get('certificate_deleted')); //save status update into session
        } else {
            Session::set('statusUpdate', sysTranslations::get('certificate_not_deleted')); //save status update into session
        }
 
    Router::redirect(ADMIN_FOLDER . '/devices/bewerken/' . $oCertificate->deviceId);

}  else {

    Router::redirect(ADMIN_FOLDER . '/devices');
}

# include template
include_once getAdminView('layout');