<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = 'Login ' . CLIENT_URL;

$sReferrer = Session::get("loginReferrer", ADMIN_FOLDER . "/");

$oGa = new GoogleAuthenticator();

if (http_get('param1') == 'fastlogin') {
    if (($oCurrentUser && $oCurrentUser->isSuperAdmin()) || !empty($_SESSION['fastLoginEnabled'])) {
        $oUser = UserManager::getUserById(http_get('param2'));
        if ($oUser && UserManager::loginByUser($oUser)) {
            $_SESSION['fastLoginEnabled'] = true;
            http_redirect($sReferrer);
        }
    }
}

# user already logged in?
if ($oCurrentUser) {

    # send to admin
    http_redirect($sReferrer);
}
$bShowLoginAttemptsWarning = false;
$bDeactivation = false;

$aBackstretchUrls = [];
if (class_exists('LoginBackgroundsWebserviceManager')) {
    //get api backgrounds
    $aLoginBackgrounds = [];
    if (!empty($aLoginBackgrounds)) {
        foreach ($aLoginBackgrounds as $oLoginBackground) {
            $aBackstretchUrls[] = '"' . $oLoginBackground['imageUrl'] . '"';
        }
    }
}

if (http_get('param1') == "2-step-authentication" && is_numeric(Session::get('2-step-userId'))) {

    # STEP 2 AUTHENTICATION
    $oUser = UserManager::getUserById(Session::get('2-step-userId'));
    if (!$oUser) {
        http_redirect("/dashboard");
    }

    $bShowQRCode = false;

    // set system language for this user
    sysTranslations::setTmpLanguageId($oUser->systemLanguageId);

    if (!empty(Request::postVar('code')) && CSRFSynchronizerToken::validate()) {
        $oGoogleAuthenticator = new GoogleAuthenticator();

        if ($oUser && !empty($oUser->twoStepSecret)) {
            if (($iTimeslice = $oGoogleAuthenticator->verifyCode($oUser->twoStepSecret, Request::postVar('code')))) {
                UserManager::updateUserGoogleAuthVerified($oUser->userId, 1);
                // check token on used tokens list and check timeslice
                $aTokens = Used2StepVerificationTokenManager::getUsedTokensByTokenOrTimeslice(Request::postVar('code'), $iTimeslice, $oUser->created, $oUser->userId);
                if (!empty($aTokens)) {
                    die('Something went wrong, contact your administrator');
                }

                // register token to disable replay attack
                Used2StepVerificationTokenManager::secureTokenUser(Request::postVar('code'), $iTimeslice, $oUser->created, $oUser->userId);

                // set session secured_login
                $_SESSION['secured_login'] = true;

                // reset login attempts
                AccessLogManager::resetLoginAttempts($oCurrentAccessLog);
                UserManager::unlockUser($oUser, '');

                $_SESSION['oCurrentUser'] = $oUser;
                http_redirect('/dashboard');
            } else {
                if (AccessLogManager::addLoginAttempt($oCurrentAccessLog, 'Username: `' . $oUser->username . '`')) {
                    // IP is blocked or tried to many times (again), lock user to block brute force attack
                    UserManager::lockUserByUsername($oUser->username, 'Too many 2 Step Verification attempts');
                    UserManager::logout('/dashboard');
                }

                # User used correct login
                if ($oUser->twoStepSecretVerified == 0) {
                    # No Google Auth Code set? Let user set it before continuing!
                    $s2StepSecret = $oGa->createSecret();
                    $bShowQRCode  = true;
                    $oUser->setGoogleAuthCode($s2StepSecret);
                    $sQRCodeUrl = $oGa->getQRCodeGoogleUrl($oUser->username, $oUser->twoStepSecret, CLIENT_NAME);
                }
                $bShowLoginAttemptsWarning = true;
            }
        }
    } else {
        # User used correct login
        if ($oUser->twoStepSecretVerified == 0) {
            # No Google Auth Code set? Let user set it before continuing!
            $s2StepSecret = $oGa->createSecret();
            $bShowQRCode  = true;
            $oUser->setGoogleAuthCode($s2StepSecret);
            $sQRCodeUrl = $oGa->getQRCodeGoogleUrl($oUser->username, $oUser->twoStepSecret, CLIENT_NAME);
        }
    }

    $bLoginEnabled      = $oCurrentAccessLog->loginEnabled();
    $bLoginAttemptsLeft = $oCurrentAccessLog->getLoginAttemptsLeft();

    // include right template
    include_once getAdminView('layout-2-step-authentication');
} else {
    if (!empty(Request::postVar('username')) && !empty(Request::postVar('password')) && Request::postVar('login_form') == 'send' && CSRFSynchronizerToken::validate()) {
        $oUser = UserManager::checkLogin(Request::postVar('username'), Request::postVar('password'));
        if (!empty($oUser)) {

            if ($oUser->deactivation == 1) {
                $bDeactivation = true;
            } else {
                $a2StepVerificationWhitelistIps = explode(',', TWO_STEP_VERIFICATION_WHITELIST_IPS);
                if (Settings::exists('2StepForced')) {
                    $iForceTwoStepGlobal = Settings::get('2StepForced');
                }
                if ((!$oUser->twoStepEnabled && !$iForceTwoStepGlobal) || in_array((isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : -1), $a2StepVerificationWhitelistIps)) {
                    if (UserManager::loginByUser($oUser)) {
                        // reset login attempts
                        AccessLogManager::resetLoginAttempts($oCurrentAccessLog);
                        UserManager::unlockUser($oUser, '');
                        http_redirect('/dashboard');
                    }
                }

                // user exists, set in session and continue to 2-step authentication
                Session::set('2-step-userId', $oUser->userId);

                // redirect to 2-step-authentication
                http_redirect(ADMIN_FOLDER . '/login/2-step-authentication');
            }
        } else {
            if (AccessLogManager::addLoginAttempt($oCurrentAccessLog, 'Username: `' . _e(Request::postVar('username')) . '`')) {
                // if an actual user is found, block that user if it should be locked
                $oUser = UserManager::getUserByUsername(Request::postVar('username'));
                if ($oUser) {
                    //UserManager::lockUserByUsername($oUser->username, 'Too many login attempts');
                }
            }
            $bShowLoginAttemptsWarning = true;
        }
    }

    $bLoginEnabled      = $oCurrentAccessLog->loginEnabled();
    $bLoginAttemptsLeft = $oCurrentAccessLog->getLoginAttemptsLeft();

    // include right template
    include_once getAdminView('layout-login');
}
?>
