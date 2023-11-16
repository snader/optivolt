<?php

# check if controller is required by index.php 
if (!defined('ACCESS')) {
    die;
}

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = "Redirect";
$oPageLayout->sModuleName  = "Redirect";

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    # set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oRedirect = RedirectManager::getRedirectById(http_get("param2"));
        if (empty($oRedirect)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oRedirect       = new Redirect();
        $oRedirect->type = http_get('type');
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oRedirect->_load($_POST);

        # if object is valid, save
        if ($oRedirect->isValid()) {
            RedirectManager::saveRedirect($oRedirect); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('redirectsSaved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oRedirect->redirectId . '&type=' . http_get('type'));
        } else {
            Debug::logError("", "Redirect module php validate error", __FILE__, __LINE__, "Tried to save Redirect with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = 'Redirect item is niet opgeslagen, niet alle velden zijn (juist) ingevuld';
        }
    }

    $oPage = PageManager::getPageByUrlPath('/');

    $oPageLayout->sViewPath = getAdminView('redirect_form', 'redirect');
} # set object online/offline
elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline     = http_get("online", 0); //no value, set offline by default
    $bAjax       = http_get("ajax", false); //controller requested by ajax
    $iredirectId = http_get("param2");
    $oResObj     = new stdClass(); //standard class for json feedback
    # update online for object
    if (is_numeric($iredirectId)) {
        $oResObj->success    = RedirectManager::updateOnlineByredirectId($bOnline, $iredirectId);
        $oResObj->redirectId = $iredirectId;
        $oResObj->online     = $bOnline;
    }

    # redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '');
    }

    die(json_encode($oResObj));
} elseif (http_get("param1") == 'ajax-testRedirect') {
    if(!CSRFSynchronizerToken::validate()){
        die('');
    }
    # test expression
    $sOutput = '';
    if (http_get('pattern') && http_get('testUrl')) {
        if (preg_match('#' . http_get('pattern') . '#i', http_get('testUrl'))) {
            $sOutput = sysTranslations::get('redirectsMatchFound') . ': ' . preg_replace('#' . http_get('pattern') . '#i', http_get('newUrl'), http_get('testUrl'));
        } else {
            $sOutput = sysTranslations::get('redirectsMatchNotFound');
        }
    }
    die($sOutput);
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oRedirect = RedirectManager::getRedirectById(http_get("param2"));
        }

        if (!empty($oRedirect) && RedirectManager::deleteRedirect($oRedirect)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('redirectDeleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('redirectNotDeleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # display overview
else {
    $aAllRedirects           = RedirectManager::getAllRedirectsByType(Redirect::TYPE_SPECIFIC);
    $aAllExpressionRedirects = RedirectManager::getAllRedirectsByType(Redirect::TYPE_EXPRESSION);
    $oPageLayout->sViewPath  = getAdminView('redirects_overview', 'redirect');
}

# include template
include_once getAdminView('layout');
?>