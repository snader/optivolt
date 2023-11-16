<?php

// check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

// secure that the module may not be executed on non-local environments
if (!defined('ENVIRONMENT') || in_array(ENVIRONMENT, ['production', 'acceptance'])) {
    die('Be aware, modules should always be installed locally');
}

// reset crop settings
Session::clear('aCropSettings');

// set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = "Module generator";
$oPageLayout->sModuleName  = "Module generator";

// get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
Session::clear('statusUpdate'); //remove statusupdate, always show once

$oModuleGeneratorItem = new ModuleGeneratorItem();

// action = save
if (Request::postVar("action") == 'save') {
    // load data in object
    $oModuleGeneratorItem->_load($_POST);

    // if object is valid, save
    if ($oModuleGeneratorItem->isValid()) {
        ModuleGeneratorItemBuilder::build($oModuleGeneratorItem); //save object
        Session::set('statusUpdate', sysTranslations::get('moduleGenerator_saved')); //save status update into session
        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    } else {
        Debug::logError("", "ModuleGenerator module php validate error", __FILE__, __LINE__, "Tried to save ModuleGenerator with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
        Session::set('statusUpdate', sysTranslations::get('moduleGenerator_not_saved'));
    }
}

$oPageLayout->sViewPath = getAdminView('moduleGenerator_form', 'moduleGenerator');

// include template
include_once getAdminView('layout');