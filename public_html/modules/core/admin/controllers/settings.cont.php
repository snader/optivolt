<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('settings_settings');
$oPageLayout->sModuleName  = sysTranslations::get('settings_settings');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
$oPageLayout->sViewPath = getAdminView('settings/settings_form');

if (http_post('action') == 'save') {


    // handle certain field types
    $aUrlFields   = [];
    $aAdminFields = [];

    $b2StepForced = 0;
    foreach (http_post('settings', []) AS $sName => $sValue) {
        // handle url fields
        if (in_array($sName, $aUrlFields)) {
            $sValue = addHttp($sValue);
        }

        // handle admin config fields
        if (in_array($sName, $aAdminFields)) {
            if ($oCurrentUser->isAdmin()) {
                $oSetting = SettingManager::getSettingByName($sName);
                if ($oSetting) {
                    $oSetting->value = $sValue;
                    SettingManager::saveSetting($oSetting);
                }
            }
            continue;
        }

        if ($sName == '2StepForced' && $sValue == 1) {
            $b2StepForced = 1;
        }

        // get setting and save
        $oSetting = SettingManager::getSettingByName($sName);
        if ($oSetting) {
            $oSetting->value = $sValue;
            SettingManager::saveSetting($oSetting);
        }
    }

    // save 2 step forced
    $oSetting = SettingManager::getSettingByName('2StepForced');
    if ($oSetting) {
        $oSetting->value = $b2StepForced;
        SettingManager::saveSetting($oSetting);
    }



    if ((empty($_POST['settings']['contactLatitude']) || empty($_POST['settings']['contactLongitude'])) && Settings::exists('clientStreet', true) && Settings::exists('clientPostalCode', true) && Settings::exists('clientCity', true)) {
        // exception for lat long
        //$aLatLong = getLatLong(Settings::get('clientStreet', true) . ', ' . Settings::get('clientPostalCode', true) . ' ' . Settings::get('clientCity', true));
    }

    if (Settings::exists('contactLatitude')) {
        //$oSetting        = SettingManager::getSettingByName('contactLatitude');
        //$oSetting->value = !empty($_POST['settings']['contactLatitude']) ? $_POST['settings']['contactLatitude'] : $aLatLong['latitude'];
        //SettingManager::saveSetting($oSetting);
    }

    if (Settings::exists('contactLongitude')) {
        //$oSetting        = SettingManager::getSettingByName('contactLongitude');
        //$oSetting->value = !empty($_POST['settings']['contactLongitude']) ? $_POST['settings']['contactLongitude'] : $aLatLong['longitude'];
        //SettingManager::saveSetting($oSetting);
    }

    if (Settings::exists('orderLogoImage')) {
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $oSetting = SettingManager::getSettingByName('orderLogoImage');

            # upload file or return error
            $oUpload = new Upload($_FILES['logo'], Setting::IMAGES_PATH . "/original/", 'order-logo', ['jpg', 'png', 'gif', 'jpeg'], true);

            # save image to database on success
            if ($oUpload->bSuccess === true) {

                # make Image object and save
                $oImage = new Image();

                $oImageFile            = new ImageFile();
                $oImageFile->link      = $oUpload->sNewFilePath;
                $oImageFile->title     = $oUpload->sFileName;
                $oImageFile->name      = $oUpload->sNewFileBaseName;
                $oImageFile->mimeType  = $oUpload->sMimeType;
                $oImageFile->size      = $oUpload->iSize;
                $oImageFile->reference = 'original';
                $aImageFiles[]         = clone $oImageFile;

                # make cms thumb
                if (ImageManager::resizeImage(DOCUMENT_ROOT . '/' . $oImageFile->getLinkWithoutQueryString(), DOCUMENT_ROOT . Setting::IMAGES_PATH . '/cms_thumb/' . $oImageFile->name, 100, 100, $sErrorMsg, false)) {
                    $oImageFileThumb            = clone $oImageFile;
                    $oImageFileThumb->link      = Setting::IMAGES_PATH . '/cms_thumb/' . $oImageFile->name;
                    $oImageFileThumb->name      = pathinfo($oImageFileThumb->link, PATHINFO_BASENAME);
                    $oImageFileThumb->size      = filesize(DOCUMENT_ROOT . $oImageFileThumb->getLinkWithoutQueryString());
                    $oImageFileThumb->reference = 'cms_thumb';
                    $aImageFiles[]              = clone $oImageFileThumb;
                }

                # make resizeage::IMAGES_PATH
                if (ImageManager::resizeImage(DOCUMENT_ROOT . '/' . $oImageFile->getLinkWithoutQueryString(), DOCUMENT_ROOT . Setting::IMAGES_PATH . '/resize/' . $oImageFile->name, 200, 75, $sErrorMsg, false)) {
                    $oImageFileThumb            = clone $oImageFile;
                    $oImageFileThumb->link      = Setting::IMAGES_PATH . '/resize/' . $oImageFile->name;
                    $oImageFileThumb->name      = pathinfo($oImageFileThumb->link, PATHINFO_BASENAME);
                    $oImageFileThumb->size      = filesize(DOCUMENT_ROOT . $oImageFileThumb->getLinkWithoutQueryString());
                    $oImageFileThumb->reference = 'resize';
                    $aImageFiles[]              = clone $oImageFileThumb;
                }
                $oImage->setImageFiles($aImageFiles); //set imageFiles to Image object
                # save image
                ImageManager::saveImage($oImage);
                $oSetting->value = '' . $oImage->imageId;
                SettingManager::saveSetting($oSetting);
            }
        }
    }

    http_redirect(getCurrentUrl());
} elseif (http_get('param1') === 'delete-order-logo') {
    $oSetting        = SettingManager::getSettingByName('orderLogoImage');
    $iImageId        = db_int($oSetting->value);
    $oSetting->value = null;
    SettingManager::saveSetting($oSetting);
    $oImage = ImageManager::getImageById($iImageId);
    ImageManager::deleteImage($oImage);
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
}

# include default template
include_once getAdminView('layout');
?>