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
$oPageLayout->sWindowTitle = sysTranslations::get('system');
$oPageLayout->sModuleName  = sysTranslations::get('system');

# get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
Session::clear('statusUpdate'); //remove statusupdate, always show once

# handle add/edit
if (Request::param('ID') == 'bewerken' || Request::param('ID') == 'toevoegen') {

    # set crop referrer for pages module
    Session::set('cropReferrer', ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . Request::param('OtherID'));

    if (Request::param('ID') == 'bewerken' && is_numeric(Request::param('OtherID'))) {

        $oSystemReport = SystemReportManager::getSystemReportById(Request::param('OtherID'));

        if (!$oSystemReport) {
            Router::redirect(ADMIN_FOLDER . "/");
        }
        # is editable?
        $aSubSystemReports = $oSystemReport->getSubSystemReports();


    } else {

        $oSystemReport             = new SystemReport();
        $oSystemReport->userId = UserManager::getCurrentUser()->userId;
        if (http_get('systemId') && is_numeric(http_get('systemId'))) {
            $oSystem = SystemManager::getSystemById(http_get('systemId'));
            if ($oSystem) {
                $oSystemReport->systemId = $oSystem->systemId;
            } else {
                Session::set('statusUpdate', sysTranslations::get('system_not_edited')); //save status update into session
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
            }
        }
    }

    if (Request::postVar("saveImgNotice") == 'saveImgNotice') {
        $oSystemReport->notice = _e(Request::postVar("imgNotice"));
        if ($oSystemReport->isValid()) {
            SystemReportManager::saveSystemReport($oSystemReport); //save item

            saveLog(
                ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemReport->systemReportId,
                'Systeemreport afb. notitie systeem #' . $oSystemReport->systemId . ' (' . $oSystemReport->notice . ')',
                arrayToReadableText(object_to_array($oSystemReport))
              );
        }
    }

    if (
        Request::postVar("action") == 'saveNotice' && http_post('systemId') && is_numeric(http_post('systemId')) && !empty(trim(http_post('notice')))
    ) {
        $oSystem = SystemManager::getSystemById(http_post('systemId'));
        $oSystem->notice = _e(trim(http_post('notice'))) . ' (' . date("Y") . ')' . PHP_EOL . $oSystem->notice;
        if ($oSystem->isValid()) {
            SystemManager::saveSystem($oSystem);
            Session::set('statusUpdate', 'Opmerking toegevoegd'); //save status update into session

            saveLog(
                ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemReport->systemReportId,
                ' Systeemreport opmerking toegevoegd bij #' . $oSystem->systemId . ' (' . $oSystem->name . ')',
                arrayToReadableText(object_to_array($oSystem))
              );

            Router::redirect(getCurrentUrl());
        }
    }

    # action = save
    if (Request::postVar("action") == 'save') {

        $sNewName = _e(http_post('columnA'));
        if ($oSystemReport->columnA != $sNewName) {
            // RENAME IMAGES

            $aImages = $oSystemReport->getImages();
            foreach ($aImages as $oImage) {
                $aImageFiles = $oImage->getImageFiles();
                foreach ($aImageFiles as $oImageFile) {
                    if ($oImageFile->title == $oSystemReport->columnA) {
                        ImageManager::updateImageFilesTitleByImage($sNewName, $oImageFile->imageId);
                    }
                }
            }
        }


        # load data in object
        $oSystemReport->_load($_POST);


        # if object is valid, save
        if ($oSystemReport->isValid()) {
            SystemReportManager::saveSystemReport($oSystemReport); //save item

            saveLog(
                ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemReport->systemReportId,
                ' Systeemreport opgeslagen systeem #' . $oSystemReport->systemId . ' (' . SystemManager::getSystemById($oSystemReport->systemId)->name . ')',
                arrayToReadableText(object_to_array($oSystemReport))
              );

            // save extra rows of system reports
            if (isset($_POST['systemReportIdExtra'])) {

                foreach ($_POST['systemReportIdExtra'] as $iKey => $iSystemReportId) {

                    $oSubSystemReport = new SystemReport();
                    if (!empty($iSystemReportId) && is_numeric($iSystemReportId)) {
                        $oSubSystemReport = SystemReportManager::getSystemReportById($iSystemReportId);

                        $sNewName = _e($_POST['columnAextra'][$iKey]);

                        if ($oSubSystemReport->columnA != $sNewName) {
                            // RENAME IMAGES
                            $aImages = $oSystemReport->getImages(); // get images from parent
                            foreach ($aImages as $oImage) {
                                $aImageFiles = $oImage->getImageFiles();
                                foreach ($aImageFiles as $oImageFile) {
                                    if ($oImageFile->title == $oSubSystemReport->columnA) {
                                        ImageManager::updateImageFilesTitleByImage($sNewName, $oImageFile->imageId);
                                    }
                                }
                            }
                        }


                    }

                    if (!empty($_POST['faseAextra'][$iKey]) && !empty($_POST['faseBextra'][$iKey]) && !empty($_POST['faseCextra'][$iKey])) {

                        $oSubSystemReport->parentId = $oSystemReport->systemReportId;
                        $oSubSystemReport->systemId = $oSystemReport->systemId;
                        $oSubSystemReport->columnA = $_POST['columnAextra'][$iKey];
                        $oSubSystemReport->faseA = $_POST['faseAextra'][$iKey];
                        $oSubSystemReport->faseB = $_POST['faseBextra'][$iKey];
                        $oSubSystemReport->faseC = $_POST['faseCextra'][$iKey];

                        if (!empty($_POST['faseDextra'][$iKey]) && !empty($_POST['faseEextra'][$iKey]) && !empty($_POST['faseFextra'][$iKey])) {
                            $oSubSystemReport->faseD = $_POST['faseDextra'][$iKey];
                            $oSubSystemReport->faseE = $_POST['faseEextra'][$iKey];
                            $oSubSystemReport->faseF = $_POST['faseFextra'][$iKey];
                        }

                  
                        if ($oSubSystemReport->isValid()) {
                            SystemReportManager::saveSystemReport($oSubSystemReport); //save subitem

                            
                        }
                    }
                }
            }


            ////

            Session::set('statusUpdate', sysTranslations::get('system_report_saved')); //save status update into session

            

            if (!empty(trim(http_post('systemNotice')))) {
                $oSystem = SystemManager::getSystemById(http_post('systemId'));
                $oSystem->notice = _e(trim(http_post('systemNotice'))) . ' (' . date("Y") . ')' . PHP_EOL . $oSystem->notice;
                if ($oSystem->isValid()) {
                    SystemManager::saveSystem($oSystem);

                    saveLog(
                        ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oSystem->systemId,
                        ' Systeemopmerking toegevoegd bij #' . $oSystem->systemId . ' (' . $oSystem->name . ')',
                        arrayToReadableText(object_to_array($oSystem))
                      );

                    Session::set('statusUpdate', sysTranslations::get('system_report_saved') . ' en opmerking toegevoegd'); //save status update into session

                }
            }

            if (substr_count($_POST['save'], 'Systemen') == 1) {
                Router::redirect(ADMIN_FOLDER . '/klanten/systems/' . $oSystemReport->getSystem()->getLocation()->getCustomer()->customerId);
            }

            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemReport->systemReportId);
        } else {
            Debug::logError("", "System module php validate error", __FILE__, __LINE__, "Tried to save System with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('system_not_saved');
        }
    }

    # action saveFile
    if (Request::postVar("action") == 'saveFile') {
        if (CSRFSynchronizerToken::validate()) {

            $bCheckMime = true;

            // for upload from MFUpload
            if (Request::postVar("MFUpload")) {
                $oResObj          = new stdClass();
                $oResObj->success = false;
                $bCheckMime       = true;
            }

            // upload file or return error
            $oUpload = new Upload($_FILES['file'], SystemReport::FILES_PATH . '/', (Request::postVar('file_title') != '' ? Request::postVar('file_title') : null), null, $bCheckMime);

            // save file to database on success
            if ($oUpload->bSuccess === true) {

                // make File object and save
                $oFile           = new File();
                $oFile->name     = $oUpload->sNewFileBaseName;
                $oFile->mimeType = $oUpload->sMimeType;
                $oFile->size     = $oUpload->iSize;
                $oFile->link     = $oUpload->sNewFilePath;
                $oFile->title    = Request::postVar('title') == '' ? $oUpload->sFileName : Request::postVar('title');

                // save file
                FileManager::saveFile($oFile);

                // save page file relation
                SystemReportManager::saveSystemFileRelation($oSystemReport->systemReportId, $oFile->mediaId);

                // for MFUpload
                if (Request::postVar("MFUpload")) {
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
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemReport->systemReportId);
            } else {

                // for MFUpload
                if (Request::postVar("MFUpload")) {
                    $oResObj->errorMsg = $oUpload->getErrorMessage();
                    print json_encode($oResObj);
                    die;
                }

                Session::set('statusUpdate', sysTranslations::get('global_file_not_uploaded') . $oUpload->getErrorMessage()); //error uploading file
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemReport->systemReportId);
            }
        }
    }

    # set settings for file management
    $oFileManagerHTML                      = new FileManagerHTML();
    $oFileManagerHTML->aFiles              = $oSystemReport->getFiles('all');
    $oFileManagerHTML->sUploadUrl          = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemReport->systemReportId;
    $oFileManagerHTML->bMultipleFileUpload = false;
    $oFileManagerHTML->bTitleRequired      = false;
    $oFileManagerHTML->onlineChangeable                     = false;

    # action saveImage
    if (
        Request::postVar("action") == 'saveImage' && is_array($_FILES['image']) && isset($_FILES['image']['error']) && $_FILES['image']['error'] == 0
    ) {


        if (CSRFSynchronizerToken::validate()) {

            $bCheckMime = true;

            // for upload from MFUpload
            if (Request::postVar("MFUpload")) {
                $oResObj          = new stdClass();
                $oResObj->success = false;
                $bCheckMime       = true;
            }

            $aImageSettings = TemplateSettings::get('systems', 'images');

            
            $aParts = explode('.', $_FILES['image']['name']);
            $sOriginalFlirName = $aParts[0];
            
            // upload file or return error
            $oUpload = new Upload($_FILES['image'], $aImageSettings['imagesPath'] . "/" . $aImageSettings['originalReference'] . "/", (Request::postVar('title') != '' ? Request::postVar('title') : null), ['jpg', 'png', 'gif', 'jpeg'], $bCheckMime);

            // save image to database on success
            if ($oUpload->bSuccess === true) {
                $sTitle = Request::postVar('title') ? Request::postVar('title') : '';
                $oImage = ImageManager::handleImageUpload($oUpload, $aImageSettings, $sTitle, $aErrorMsgs, $sOriginalFlirName);

                if ($oImage) {
                    # save image relation
                    SystemReportManager::saveSystemImageRelation($oSystemReport->systemReportId, $oImage->imageId);
                }

                // for MFUpload
                if (Request::postVar("MFUpload")) {
                    $oResObj->success = true;
                    $oImageFileThumb  = $oImage->getImageFileByReference('cms_thumb');
                    // add some extra values
                    $oImageFileThumb->isOnlineChangeable  = $oImage->isOnlineChangeable();
                    $oImageFileThumb->isCropable          = $oImage->isCropable();
                    $oImageFileThumb->isEditable          = $oImage->isEditable();
                    $oImageFileThumb->isDeletable         = $oImage->isDeletable();
                    $oImageFileThumb->hasNeededImageFiles = $oImage->hasImageFiles(TemplateSettings::get('systems', 'images/neededImageFiles'));

                    $oResObj->imageFile = $oImageFileThumb; // for adding image to list (last imageFile object)
                    print json_encode($oResObj);
                    die;
                }


                // back to edit
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemReport->systemReportId);
            } else {

                // for MFUpload
                if (Request::postVar("MFUpload")) {
                    $oResObj->errorMsg = $oUpload->getErrorMessage();
                    print json_encode($oResObj);
                    die;
                }

                Session::set('statusUpdate', sysTranslations::get('global_image_not_uploaded') . $oUpload->getErrorMessage()); //error uploading file
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemReport->systemReportId);
            }
        }
    }

    # set settings for image management
    $oImageManagerHTML                                       = new ImageManagerHTML();
    $oImageManagerHTML->bMultipleFileUpload                  = true;

    $oImageManagerHTML->onlineChangeable                     = false;
    $oImageManagerHTML->changeOnlineLink                     = false;
    $oImageManagerHTML->cropable                             = false;
    $oImageManagerHTML->aMultipleFileUploadAllowedExtensions = ['png', 'jpg', 'jpeg'];
    $oImageManagerHTML->aImages                              = $oSystemReport->getImages('all');
    $oImageManagerHTML->cropLink                             = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/crop-image';
    $oImageManagerHTML->sUploadUrl                           = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemReport->systemReportId;
    $oImageManagerHTML->aNeededImageFileReferences           = TemplateSettings::get('systems', 'images/neededImageFiles');
    $oImageManagerHTML->bShowCropAfterUploadOption           = false;

    $oPageLayout->sViewPath = getAdminView('systemReports/systemReport_form', 'systems');
} elseif (Request::param('ID') == 'verwijderen' && is_numeric(Request::param('OtherID'))) {
    if (CSRFSynchronizerToken::validate()) {
        if (is_numeric(Request::param('OtherID'))) {
            $oSystemReport = SystemReportManager::getSystemReportById(Request::param('OtherID'));
        }
        if ($oSystemReport && SystemReportManager::deleteSystemReport($oSystemReport)) {

            saveLog(
                ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemReport->systemReportId,
                ' Systeemreport verwijderen systeem #' . $oSystemReport->systemId . ' (' . SystemManager::getSystemById($oSystemReport->systemId)->name . ')',
                arrayToReadableText(object_to_array($oSystemReport))
              );

            Session::set('statusUpdate', sysTranslations::get('system_report_deleted')); //save status update into session
        } else {
            Session::set('statusUpdate', sysTranslations::get('system_report_not_deleted')); //save status update into session
        }

    }
    Router::redirect(ADMIN_FOLDER . '/systems/bewerken/' . $oSystemReport->systemId);
} elseif (Request::param('ID') == 'crop-image' && is_numeric(Request::getVar("imageId"))) {
    $oImage = ImageManager::getImageById(Request::getVar("imageId"));

    if (!$oImage) {
        Session::set('statusUpdate', sysTranslations::get('global_image_not_available')); //error getting image
        Router::redirect(Session::get('cropReferrer'));
    }

    $aImageSettings = TemplateSettings::get('systems', 'images');
    $sReferrer      = Session::get('cropReferrer');
    $sReferrerText  = 'item bewerken';
    $aCropSettings  = ImageManager::handleImageCropSettings($oImage, $aImageSettings, $sReferrer, $sReferrerText);

    // add setting to session in an array
    Session::set('aCropSettings', $aCropSettings);

    Router::redirect(ADMIN_FOLDER . '/crop');
} elseif (Request::param('ID') == 'image-list' && is_numeric(Request::param('OtherID'))) {
    $oSystemReport = SystemReportManager::getSystemReportById(Request::param('OtherID'));

    if (!$oSystemReport) {
        die;
    }

    $aImages     = $oSystemReport->getImages('all');
    $aImageFiles = [];
    $aLinks      = [];
    foreach ($aImages as $oImage) {
        $oImageFile = $oImage->getImageFileByReference('detail');
        if (!$oImageFile) {
            continue;
        }
        $oLink        = new stdClass();
        $oLink->title = $oImageFile->title ? $oImageFile->title : $oImageFile->name;
        $oLink->value = CLIENT_HTTP_URL . $oImageFile->link;
        $aLinks[]     = $oLink;
    }

    die(json_encode($aLinks));
} else {

    $aSystemFilter['year'] = date('Y');

    #display overview
    $aSystemReports             = SystemReportManager::getSystemReportsByFilter($aSystemFilter);

    $oPageLayout->sViewPath = getAdminView('systemReports/systemReports_overview_' . $oSystem->systemTypeId, 'systems');
}

# include template
include_once getAdminView('layout');
