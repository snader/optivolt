<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout                = new PageLayout();
$oPageLayout->sWindowTitle  = sysTranslations::get('templates_management');
$oPageLayout->sTemplateName = sysTranslations::get('templates_management');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle templateFilter
$aTemplateFilter = http_session('templateFilter');
if (http_post('filterForm')) {
    $aTemplateFilter            = http_post('templateFilter');
    $_SESSION['templateFilter'] = $aTemplateFilter;
}

if (http_post('resetFilter') || empty($aTemplateFilter)) {
    unset($_SESSION['templateFilter']);
    $aTemplateFilter                = [];
    $aTemplateFilter['description'] = '';
}

# handle perPage
if (http_post('setPerPage')) {
    $_SESSION['templatesPerPage'] = http_post('perPage');
}

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oTemplate = TemplateManager::getTemplateById(http_get("param2"));
        if (!$oTemplate) {
            http_redirect(ADMIN_FOLDER . "/");
        }

        # is editable?
        if (!$oTemplate->isEditable()) {
            $_SESSION['statusUpdate'] = sysTranslations::get('templates_status_not_editable'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
        }
    } else {
        $oTemplate             = new Template ();
        $oTemplate->languageId = AdminLocales::language();

        if (is_numeric(http_get('copyFrom'))) {
            $oTemplateCopy = TemplateManager::getTemplateById(http_get('copyFrom'));

            if (!empty($oTemplateCopy)) {
                $oTemplate              = clone $oTemplateCopy;
                $oTemplate->languageId  = AdminLocales::language();
                $oTemplate->templateId  = null;
                $oTemplate->description = $oTemplate->description . ' (1)';
                $oTemplate->name        = $oTemplate->name . ' (1)';
                $oTemplate->setDeletable(1);
            }
        }
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oTemplate->_load($_POST);
        $oTemplate->template = http_post('template');

        if ($oCurrentUser->isAdmin()) {
            $oTemplate->setEditable(http_post('editable'));
            $oTemplate->setDeletable(http_post('deletable'));
        }

        # strips HTML tags from SMS message
        if ($oTemplate->type == Template::TYPE_SMS) {
            $oTemplate->template = strip_tags($oTemplate->template);
        }

        # if object is valid, save
        if ($oTemplate->isValid()) {
            TemplateManager::saveTemplate($oTemplate); //save template
            $_SESSION['statusUpdate'] = sysTranslations::get('templates_status_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oTemplate->templateId);
        } else {
            Debug::logError("", "Templates template php validate error", __FILE__, __LINE__, "Tried to save Template with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('templates_field_not_completed');
        }
    }

    $oPageLayout->sViewPath = getAdminView('template_form', 'templates');
} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if (CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oTemplate = TemplateManager::getTemplateById(http_get("param2"));
        }

        if ($oTemplate && $oTemplate->isDeletable() && TemplateManager::deleteTemplate($oTemplate)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('templates_status_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('templates_status_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get("param1") == 'ajax-getTemplateVariables') {
    $oResObj          = new stdClass();
    $oResObj->success = false;

    $iTemplateGroupId = http_get('templateGroupId');
    if (!empty($iTemplateGroupId)) {
        $oTemplateGroup = TemplateManager::getTemplateGroupById($iTemplateGroupId);
        if (!empty($oTemplateGroup)) {
            $oResObj->sHtml   = nl2br($oTemplateGroup->templateVariables);
            $oResObj->success = true;
        }
    }

    die(json_encode($oResObj));
} elseif (http_get('param1') == 'ajax-sendTest') {
    $oResObj          = new stdClass();
    $oResObj->success = false;

    if (CSRFSynchronizerToken::validate()) {
        $iTemplateId = http_post('templateId');
        $sTo         = http_post('to');

        $oResObj->to = $sTo;

        if (!empty($iTemplateId) && !empty($sTo) && $oTemplate = TemplateManager::getTemplateById($iTemplateId)) {
            $oTemplate->replaceVariables(null, [], true);
            $sSubject  = $oTemplate->getSubject();
            $sMailBody = $oTemplate->getTemplate();

            # send test mail
            if ($oTemplate->type == Template::TYPE_EMAIL && MailManager::sendMail($sTo, $sSubject, $sMailBody)) {
                $oResObj->success = true;
            }
        }
    }

    die(json_encode($oResObj));
} elseif (http_get('param1') == 'ajax-checkName') {
    if (!CSRFSynchronizerToken::validate()) {
        die(json_encode(['status' => false]));
    }
    # check if name exists
    $oTemplate = TemplateManager::getTemplateByName(http_get('name'), AdminLocales::language());
    if ($oTemplate && $oTemplate->templateId != http_get('templateId')) {
        echo 'false';
    } else {
        echo 'true';
    }
    die;
} else {

    $iNrOfRecords = DBConnection::count('templates');
    $iPerPage     = http_session('templatesPerPage', 10);
    $iCurrPage    = http_get('page', 1);
    if (http_post('setPerPage')) {
        // reset iCurrPage on setPerPage change
        $iCurrPage = 1;
    }
    if ($iCurrPage > ($iNrOfRecords / $iPerPage) + 1) {
        // prevent non existing iCurrpage
        $iCurrPage = (round($iNrOfRecords / $iPerPage) + 1);
    }
    $iStart = (($iCurrPage - 1) * $iPerPage);
    if (!is_numeric($iCurrPage) || $iCurrPage <= 0) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    # add language to filter
    $aTemplateFilter['languageId'] = AdminLocales::language();

    $aTemplates = TemplateManager::getTemplatesByFilter($aTemplateFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;

    $oPageLayout->sViewPath = getAdminView('templates_overview', 'templates');
}

# include default template
include_once getAdminView('layout');
?>
