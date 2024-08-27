<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('user_managament');
$oPageLayout->sModuleName  = sysTranslations::get('user_managament');

# get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
Session::clear('statusUpdate'); //remove statusupdate, always show once

// reset crop settings
Session::clear('aCropSettings');

if (http_get('param1') == 'ajax' && http_get('param2') == 'reset2step') {
    if (is_numeric(Request::postVar('userId'))) {
        UserManager::reset2Fa(Request::postVar('userId'));

        return true;
    } else {
        return false;
    }

}

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

    // set crop referrer for pages module
    Session::set('cropReferrer', ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2"));

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oUser = UserManager::getUserById(http_get("param2"));
        if (!$oUser) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oUser = new User ();
    }

    # action = save
    if (Request::postVar("action") == 'save') {

        # do special things before load (because of password hashing)
        if (http_get("param1") == 'bewerken') {
            if (empty($_POST["password"])) {
                #set password from object
                $_POST['password'] = $oUser->password;
            } else {
                #hash and check password for saving later
                if (isValidPassword($_POST['password'])) {
                    $_POST['password'] = hashPasswordForDb($_POST['password']);
                } else {

                    $_POST['password'] = null;
                }
            }
        } else {
            # check password is valid
            if (isValidPassword($_POST['password'])) {
                $_POST['password'] = $_POST['password'];
            } else {
                $_POST['password'] = null;
            } //object validation will trigger error
        }

        # load data in object
        $oUser->_load($_POST);



        if ($oCurrentUser->isAdmin()) {
            $oUser->setAdministrator(Request::postVar('administrator') ? Request::postVar('administrator') : 0);
        }
        if ($oCurrentUser->isAdmin() || $oCurrentUser->isSEO()) {
            $oUser->setSEO(Request::postVar('seo') ? Request::postVar('seo') : 0);
        }

        $oUser->accountmanager = (Request::postVar('accountmanager') ? Request::postVar('accountmanager') : 0);
        $oUser->twoStepEnabled = (Request::postVar('twoStepEnabled') ? Request::postVar('twoStepEnabled') : 0);
        $oUser->twoStepCookie = (Request::postVar('twoStepCookie') ? Request::postVar('twoStepCookie') : 0);

        $bDeactivation = Request::postVar('deactivation') ? Request::postVar('deactivation') : $oUser->deactivation;
        if ($bDeactivation == 0) {
            $oUser->lockedReason = "";
        }

        if ($oUser && $oUser->isOnlineChangeable() > 1) {
            if ($oUser->deactivation == 1) {
                $sLockedReason     = $oUser->lockedReason;
                $sDeactivationDate = Date::strToDate('now')
                    ->format('%Y-%m-%d %H:%M:%S');
            } else {
                $bDeactivation = 0;
                $sLockedReason     = "";
                $sDeactivationDate = null;
            }
            UserManager::updateDeactivationByUser($bDeactivation, $oUser, $sLockedReason, $sDeactivationDate);
        }

        # if object is valid, save
        if ($oUser->isValid()) {

            UserManager::saveUser($oUser); //save user
            Session::set('statusUpdate', sysTranslations::get('user_status_saved')); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oUser->userId);
        } else {

            //Debug::logError("", "User module php validate error", __FILE__, __LINE__, "Tried to save user with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('user_status_not_saved');
        }
    }

    # mask password for form (just in case)
    $oUser->maskPass();

    $aFilter            = [];
    $aFilter['showAll'] = 1;
    $aFilter['active']  = 1;

    // aside admin can see all modules, others only the ones they have rights for
    if (!$oCurrentUser->isSuperAdmin()) {
        $aFilter['checkRights'] = true;
    }

    # get all modules for rights
    $aModules = ModuleManager::getModulesByFilter($aFilter);

    $oPageLayout->sViewPath = getAdminView('users/user_form');

    // action saveImage
    if (Request::postVar("action") == 'saveImage') {

        $bCheckMime = true;

        $aImageSettings = TemplateSettings::get('core', 'images');

        // upload file or return error
        $oUpload = new Upload($_FILES['image'], $aImageSettings['imagesPath'] . "/" . $aImageSettings['originalReference'] . "/", (Request::postVar('title') != '' ? Request::postVar('title') : null), ['jpg', 'png', 'gif', 'jpeg'], $bCheckMime);

        // save image to database on success
        if ($oUpload->bSuccess === true) {
            $sTitle = Request::postVar('title', '');
            $oImage = ImageManager::handleImageUpload($oUpload, $aImageSettings, $sTitle, $aErrorMsgs);

            // delete the old Image
            $oOldImage = $oUser->getImage();
            if (!empty($oOldImage)) {
                ImageManager::deleteImage($oOldImage);
            }

            if ($oImage) {
                // save Image
                ImageManager::saveImage($oImage);
            }

            // save object's Image relation
            UserManager::saveUserImageRelation($oUser->userId, $oImage->imageId);
            UserManager::updateUserInSession();
            // back to edit
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oUser->userId);
        } else {
            Session::set('statusUpdate', sysTranslations::get('global_image_not_uploaded') . ' ' . $oUpload->getErrorMessage()); //error uploading file
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oUser->userId);
        }
    }
    // set settings for image management
    $oImageManagerHTML                             = new ImageManagerHTML();
    $oImageManagerHTML->aImages                    = ($oUser->getImage() ? [$oUser->getImage()] : []);
    $oImageManagerHTML->cropLink                   = ADMIN_FOLDER . '/' . http_get('controller') . '/crop-image';
    $oImageManagerHTML->sUploadUrl                 = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oUser->userId;
    $oImageManagerHTML->aNeededImageFileReferences = TemplateSettings::get('core', 'images/neededImageFiles');
    $oImageManagerHTML->bShowCropAfterUploadOption = false;
    $oImageManagerHTML->onlineChangeable                     = false;
    $oImageManagerHTML->changeOnlineLink                     = false;
    $oImageManagerHTML->cropable                             = false;
    $oImageManagerHTML->sExtraUploadedLine         = '';//sysTranslations::get('global_images_displayed') . (!empty($oPage) ? ' <a href="' . $oPage->getBaseUrlPath() . '" target="_blank">' . $oPage->getShortTitle() . '</a>' : '');

} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if (is_numeric(http_get("param2"))) {
        $oUser = UserManager::getUserById(http_get("param2"));
    }

    if ($oUser && UserManager::deleteUser($oUser)) {
        Session::set('statusUpdate', sysTranslations::get('user_status_deleted')); //save status update into session
    } else {
        Session::set('statusUpdate', sysTranslations::get('user_status_not_deleted')); //save status update into session
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));

} // crop Image
elseif (http_get("param1") == 'crop-image' && is_numeric(http_get("imageId"))) {
    $oImage = ImageManager::getImageById(http_get("imageId"));

    if (!$oImage) {
        Session::set('statusUpdate', sysTranslations::get('global_image_not_available')); //error getting image
        http_redirect(Session::get('cropReferrer'));
    }

    $aImageSettings = TemplateSettings::get('core', 'images');
    $sReferrer      = Session::get('cropReferrer');
    $sReferrerText  = sysTranslations::get('user_settings');
    $aCropSettings  = ImageManager::handleImageCropSettings($oImage, $aImageSettings, $sReferrer, $sReferrerText);

    // add setting to session in an array
    Session::set('aCropSettings', $aCropSettings);

    http_redirect(ADMIN_FOLDER . '/crop');
} elseif (http_get('param1') == 'ajax-checkUsername') {

    # check if username exists
    $oUser = UserManager::getUserByUsername(http_get('username'));
    if ($oUser && $oUser->userId != http_get('userId')) {
        echo 'false';
    } else {
        echo 'true';
    }
    die;
} elseif (http_get("param1") == 'ajax-setActivation') {
    $bDeactivation = http_get("activation");

    if ($bDeactivation == 1) {
        $sDeactivationDate = Date::strToDate('now')
            ->format('%Y-%m-%d %H:%M:%S');
    } else {
        $sDeactivationDate = null;
    }
    $bAjax         = http_get("ajax", false); //controller requested by ajax
    $iUserId       = http_get("param2");
    $oUser         = UserManager::getUserById($iUserId);
    $sLockedReason = '';
    $oResObj       = new stdClass(); //standard class for json feedback
    // update online for user
    if ($oUser && $oUser->isOnlineChangeable() > 1 ) {
        $oResObj->success      = UserManager::updateDeactivationByUser($bDeactivation, $oUser, $sLockedReason, $sDeactivationDate);
        $oResObj->userId       = $iUserId;
        $oResObj->online       = $bDeactivation;
        $oResObj->reason       = $sLockedReason;
        $oResObj->deactivation = $bDeactivation ? Date::strToDate('now')
            ->format('%d-%m-%Y om %H:%M:%S') : '';
    }

    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }
    print json_encode($oResObj);
    die;
} else {

    if (Request::postVar('action') == 'unlockUser') {
        $oUser = UserManager::getUserById(Request::postVar('userId'));
        if ($oUser->locked) {
            UserManager::unlockUser($oUser, Request::postVar('unlockReason'));
        }
        http_redirect(getCurrentUrlPath());
    }

    $aUsers                 = UserManager::getUsersByFilter();
    $oPageLayout->sViewPath = getAdminView('users/users_overview');
}


# include default template
include_once getAdminView('layout');
?>