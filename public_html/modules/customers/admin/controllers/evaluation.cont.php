<?php
# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}


global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = "Evaluaties klanten";
$oPageLayout->sModuleName  = "Evaluaties klanten";

# get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");

Session::clear('statusUpdate'); //remove statusupdate, always show once

// handle filter
$aEvaluationFilter = Session::get('evaluationFilter');
if (Request::postVar('filterEvaluations')) {

    $aEvaluationFilter            = Request::postVar('evaluationFilter');
    $aEvaluationFilter['showAll'] = true; // manually set showAll to true
    Session::set('evaluationFilter', $aEvaluationFilter);
}

if (Request::postVar('resetFilter') || empty($aEvaluationFilter)) {
    Session::clear('evaluationFilter');
    $aEvaluationFilter                       = [];
    $aEvaluationFilter['q']                  = '';
    $aEvaluationFilter['showAll']            = true; // manually set showAll to true
}

if (Request::getVar('customerId') && is_numeric(Request::getVar('customerId'))) {
    $aEvaluationFilter['customerId'] = Request::getVar('customerId');
    Session::set('evaluationFilter', $aEvaluationFilter);
}

if (isset($aEvaluationFilter['customerId']) && !is_numeric($aEvaluationFilter['customerId'])) {
    unset($aEvaluationFilter['customerId']);
}


# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oEvaluation = EvaluationManager::getEvaluationById(http_get("param2"));
        if (empty($oEvaluation)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        
        
        $oEvaluation = new Evaluation();
       
        if (http_get('customerId') && CustomerManager::getCustomerById(http_get('customerId'))) {
         
           $oEvaluation->customerId = CustomerManager::getCustomerById(http_get('customerId'))->customerId;
        } else {
           $aFilter['online'] = true;
           $aAllCustomers = CustomerManager::getCustomersByFilter($aFilter);
         
        }
    }

    # action = save
    if (http_post("action") == 'save') {

        # load data in object
        $oEvaluation->_load($_POST);
        
        # if object is valid, save
        if ($oEvaluation->isValid()) {

            
            EvaluationManager::saveEvaluation($oEvaluation); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('evaluation_saved'); //save status update into session

            saveLog(
                ADMIN_FOLDER . '/evaluaties/bewerken/' . $oEvaluation->evaluationId,
                ucfirst(http_get("param1")) . ' evaluatie #' . $oEvaluation->evaluationId . ' (' . CustomerManager::getCustomerById($oEvaluation->customerId)->companyName . ')',
                arrayToReadableText($_POST)
              );

            http_redirect(ADMIN_FOLDER . '/evaluaties/bewerken/' . $oEvaluation->evaluationId);
        } else {
            
            Debug::logError("", "Evaluation module php validate error", __FILE__, __LINE__, "Tried to save Evaluation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate']['text'] = sysTranslations::get('evaluation_not_saved');
            $_SESSION['statusUpdate']['type'] = 'error';
        }
    }

    $aFilter['showAll'] = false;
    $bShowAddButton = false;
    if ($oCurrentUser->isClientAdmin() || $oCurrentUser->isSuperAdmin()) {
        $bShowAddButton = true;
        $aFilter['showAll'] = true;      
    }



    $oPageLayout->sViewPath = getAdminView('evaluations/evaluation_form', 'customers');
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {

        if (is_numeric(http_get("param2"))) {
            $oEvaluation = EvaluationManager::getEvaluationById(http_get("param2"));
        }

        # trigger error
        if (empty($oEvaluation)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('evaluation_not_deleted'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
        }

        if (!empty($oEvaluation) && EvaluationManager::deleteEvaluation($oEvaluation)) {

            saveLog(
                ADMIN_FOLDER . '/klanten/bewerken/' . $oEvaluation->customerId,
                'Locatie verwijderd #' . $oEvaluation->evaluationId . ' (' . CustomerManager::getCustomerById($oEvaluation->customerId)->companyName . ')',
                arrayToReadableText(object_to_array($oEvaluation))
              );

            $_SESSION['statusUpdate'] = sysTranslations::get('evaluation_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('evaluation_not_deleted'); //save status update into session
        }

    http_redirect(ADMIN_FOLDER . '/klanten/bewerken/' . $oEvaluation->customerId);
} # display overview
else {


    $aCustomerFilter['online'] = true;
    $aAllCustomers = CustomerManager::getCustomersByFilter($aCustomerFilter);
    $aEvaluations = EvaluationManager::getEvaluationsByFilter($aEvaluationFilter);

  
    $oPageLayout->sViewPath = getAdminView('evaluations/evaluations_overview', 'customers');

}

# include template
include_once getAdminView('layout');
?>