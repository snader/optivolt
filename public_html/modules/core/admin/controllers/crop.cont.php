<?php

// check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('crop_crop');
$oPageLayout->sModuleName  = sysTranslations::get('crop_crop');
$oPageLayout->sViewPath    = getAdminView('crop/crop_form');

// add Jcrop style sheet
$oPageLayout->addStylesheet('<link href="' . SITE_ADMIN_CORE_FOLDER . '/plugins/jCrop/jquery.Jcrop.min.css" rel="stylesheet">');

// add Jcrop code
$oPageLayout->addJavascript('<script src="' . SITE_ADMIN_CORE_FOLDER . '/plugins/jCrop/jquery.Jcrop.min.js"></script>');

// get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
// remove files, older than 3 hours, from tmp folder
$fHandle = opendir(DOCUMENT_ROOT . CropSettings::TMP_FOLDER);
while (($sFile = readdir($fHandle))) {
    if ($sFile == '.' || $sFile == '..') {
        continue;
    } else {
        // check age and remove older than 3 hours
        if ($sFile != '.gitignore' && Date::strToDate(filemtime(DOCUMENT_ROOT . CropSettings::TMP_FOLDER . '/' . $sFile))
                ->hoursDiff(new Date()) > 3) {
            unlink(DOCUMENT_ROOT . CropSettings::TMP_FOLDER . '/' . $sFile);
        }
    }
}

$aCropSettings = http_session('aCropSettings');

// set specific crop
if (http_post('action') == 'setCrop') {
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '?crop=' . http_post('crop'));
}

$iCropCurrent = http_get('crop', 0);
$iCropPrev    = $iCropCurrent > 0 ? $iCropCurrent - 1 : false;
$iCropNext    = $iCropCurrent < count($aCropSettings) - 1 ? $iCropCurrent + 1 : false;

// check if cropsettings is richt type
if (!$aCropSettings[$iCropCurrent] instanceof CropSettings) {
    http_redirect(ADMIN_FOLDER . "/");
}

// get image if there is one
$oImage = ImageManager::getImageById($aCropSettings[$iCropCurrent]->iImageId);

if ($oImage->isCropable() !== true) {
    die(sysTranslations::get('crop_not_allowed'));
}

// set if all needed crops are made
$aNeededCrops = [];
foreach ($aCropSettings[$iCropCurrent]->getCrops() AS $aCrop) {
    $aNeededCrops[] = $aCrop[3]; // reference name (fe crop_small)
}
$bHasNeededCrops = $oImage->hasImageFiles($aNeededCrops);

// count crops 2 go
$iCrops2Go = count($aCropSettings) - $iCropCurrent - 1;

// get imageFile by reference
if ($oImage) {
    $oOriginalImageFile = $oImage->getImageFileByReference($aCropSettings[$iCropCurrent]->sReferenceName);
}
if (!$oOriginalImageFile) {
    die(sysTranslations::get('crop_no_image_to_crop'));
}

// crop from this location
$sCropFromLocation = http_session('sTmpCropFromLocation', $oOriginalImageFile->getLinkWithoutQueryString());

// missing local image, try to get file from remote
if (!file_exists(DOCUMENT_ROOT . $sCropFromLocation)) {
    if (($sFileContents = file_get_contents(CLIENT_HTTP_URL . $sCropFromLocation)) !== false) {
        file_put_contents(DOCUMENT_ROOT . $sCropFromLocation, $sFileContents);
    }
}

// add space to image to make possible better crop
if (http_post('addSpace')) {
    $sTmpCropFromLocation = CropSettings::TMP_FOLDER . '/' . session_id() . '-' . $oOriginalImageFile->name;

    $iMarginT = 0;
    $iMarginR = 0;
    $iMarginB = 0;
    $iMarginL = 0;
    switch (http_post('position', 'all')) {
        case 'all':
            $iMarginT = http_post('size');
            $iMarginR = http_post('size');
            $iMarginB = http_post('size');
            $iMarginL = http_post('size');
            break;
        case 'top':
            $iMarginT = http_post('size');
            break;
        case 'right':
            $iMarginR = http_post('size');
            break;
        case 'bottom':
            $iMarginB = http_post('size');
            break;
        case 'left':
            $iMarginL = http_post('size');
            break;
    }

    $aImageSize     = getimagesize(DOCUMENT_ROOT . $sCropFromLocation);
    $iCropNewWidth  = $aImageSize[0] + $iMarginR + $iMarginL;
    $iCropNewHeight = $aImageSize[1] + $iMarginT + $iMarginB;
    if ($iCropNewWidth + $iCropNewHeight < 6000) {
        // expand canvas and set session variables
        if (ImageManager::expandCanvasToMargin(DOCUMENT_ROOT . $sCropFromLocation, DOCUMENT_ROOT . $sTmpCropFromLocation, $iMarginT, $iMarginR, $iMarginB, $iMarginL, hex2rgb(http_post('color', '#FFF')), $sErrorMsg)) {
            $_SESSION['sTmpCropFromLocation'] = $sTmpCropFromLocation;
            $_SESSION['sTmpCropFromImageId']  = $oOriginalImageFile->imageId;
        }
    } else {
        $_SESSION['statusUpdate'] = sysTranslations::get('image_resize_is_too_big'); //save status update into session
    }
    http_redirect(getCurrentUrl());
}

// new crop or reset is clicked, remove tmp file and reset session
if ((http_post('resetOriginal') && http_session('sTmpCropFromLocation')) || http_session('sTmpCropFromImageId') && http_session('sTmpCropFromImageId') != $oOriginalImageFile->imageId) {
    @unlink(DOCUMENT_ROOT . http_session('sTmpCropFromLocation')); // unlink temp image
    unset($_SESSION['sTmpCropFromLocation']); // unset session with link
    unset($_SESSION['sTmpCropFromImageId']); // unset session with link
    http_redirect(getCurrentUrl());
}

// do crop image
if (http_post("action") == "crop") {

    $iCx = Request::postVar("x");
    $iCy = Request::postVar("y");
    $iCw = Request::postVar("w");
    $iCh = Request::postVar("h");
    // check all required values
    if (!is_null($iCx) && !is_null($iCy) && !is_null($iCw) && !is_null($iCh)) {
        // make crops
        foreach ($aCropSettings[$iCropCurrent]->getCrops() AS $aCropInfo) {
            list($iCropW, $iCropH, $sCropLocation, $sReferenceName, $bShowUnderCropBox, $bAbsoluteSize, $iJpegQuality) = $aCropInfo;
            if (ImageManager::cropImage(DOCUMENT_ROOT . $sCropFromLocation, DOCUMENT_ROOT . $sCropLocation, $iCropW, $iCropH, $iCx, $iCy, $iCw, $iCh, $sErrorMsg, $bAbsoluteSize, $iJpegQuality)) {
                // get imageFile if one exists
                $oImageFile = $oImage->getImageFileByReference($sReferenceName);
                if (!$oImageFile) {
                    $oImageFile            = clone $oOriginalImageFile;
                    $oImageFile->mediaId   = null; // reset mediaId
                    $oImageFile->link      = $sCropLocation; // set link to crop location
                    $oImageFile->reference = $sReferenceName; // set reference name
                }
                $oImageFile->size = filesize(DOCUMENT_ROOT . $oImageFile->getLinkWithoutQueryString()); // set size
                // save imageFile
                ImageManager::saveImageFile($oImageFile);
            } else {
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
                $_SESSION['statusUpdate'] = sysTranslations::get('crop_couldnt_cut') . $sErrorMsg; // error occured
            }
        }

        // set status update
        $_SESSION['statusUpdate'] = sysTranslations::get('crop_cuts_saved');

        // to next image
        if (http_post('saveAndNext')) {
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '?crop=' . $iCropNext);
        }

        // back to crop screen
        if (http_post('apply')) {
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '?crop=' . $iCropCurrent);
        }

        // back to referrerUrl
        if (http_post('save')) {
            http_redirect($aCropSettings[$iCropCurrent]->sReferrer);
        }
    } else {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
        $_SESSION['statusUpdate'] = sysTranslations::get('crop_cut_not_created') . $sErrorMsg; // error occured
    }
}

// get sizes by default. can be overwrited later
list($iCw, $iCh, $iMimeType) = getimagesize(DOCUMENT_ROOT . $sCropFromLocation);

// show color picker for expanding canvas only for image/jpeg
$bShowColors = $iMimeType == IMAGETYPE_JPEG;

// cropbox size is set
if ($aCropSettings[$iCropCurrent]->getCropBoxSize() !== null) {
    list($iCx, $iCy, $iCw, $iCh) = $aCropSettings[$iCropCurrent]->getCropBoxSize();
} // set cropBoxSize here
elseif ($aCropSettings[$iCropCurrent]->bAutoCropBoxSize === true && $aCropSettings[$iCropCurrent]->getAspectRatio() !== null) {

// ratio is set, auto biggest cropbox
    ImageManager::getAutoCropBoxSize(DOCUMENT_ROOT . $sCropFromLocation, $aCropSettings[$iCropCurrent]->getAspectRatio(), $iCx, $iCy, $iCw, $iCh);
} else {
    // cropbox equals 75% image size and is displayed centered
    $iCx = $iCw * 0.25 / 2;
    $iCy = $iCh * 0.25 / 2;
    $iCw = $iCw * 0.75;
    $iCh = $iCh * 0.75;
}

// set aspect ratio if needed
if ($aCropSettings[$iCropCurrent]->getAspectRatio() !== null) {
    $sAspectRatio = 'aspectRatio: ' . (float) $aCropSettings[$iCropCurrent]->getAspectRatio() . ',';
}

// get image size for proper cropping from browser resized image
list($iImageW, $iImageH) = getimagesize(DOCUMENT_ROOT . $sCropFromLocation);

// set x2/y2 to right value
$iCx2 = $iCx + $iCw;
$iCy2 = $iCy + $iCh;

// set min size
$sMinSizeCalc = '';
$sMinSize     = '';
if (is_array($aCropSettings[$iCropCurrent]->getMinSize())) {
    list($iMinSizeW, $iMinSizeH) = $aCropSettings[$iCropCurrent]->getMinSize();
    $sMinSizeCalc = 'var iMinSizeW = Math.round(' . $iMinSizeW . ' * resizeRatio);' . "\n";
    $sMinSizeCalc .= 'var iMinSizeH = Math.round(' . $iMinSizeH . ' * resizeRatio);';
    $sMinSize     = 'minSize: [iMinSizeW,iMinSizeH],';
}

// set max size
$sMaxSizeCalc = '';
$sMaxSize     = '';
if (is_array($aCropSettings[$iCropCurrent]->getMaxSize())) {
    list($iMaxSizeW, $iMaxSizeH) = $aCropSettings[$iCropCurrent]->getMaxSize();
    $sMaxSizeCalc = 'var iMaxSizeW = Math.round(' . $iMaxSizeW . ' * resizeRatio);' . "\n";
    $sMaxSizeCalc .= 'var iMaxSizeH = Math.round(' . $iMaxSizeH . ' * resizeRatio);';
    $sMaxSize     = 'maxSize: [iMaxSizeW,iMaxSizeH],';
}

// set max preview width
if (is_numeric($aCropSettings[$iCropCurrent]->iMaxPreviewWidth)) {
    $iMaxPreviewW = $aCropSettings[$iCropCurrent]->iMaxPreviewWidth;
} else {
    $iMaxPreviewW = 'null';
}

// include default template
include_once getAdminView('layout');
?>