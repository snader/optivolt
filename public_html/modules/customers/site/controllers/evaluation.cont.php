<?php

/*
 * controller for evaluations
 */

// make pageLayout Object
$oPageLayout = new PageLayout();

// get page by name 
$oPage = PageManager::getPageByName('evaluation');

if (empty($oPage) || !$oPage->online) {
    showHttpError('404');
}

//  een lange sha256 string, checken voor ingelogd check
if (!empty(http_get("param1"))) {

    if (!preg_match("/^([a-f0-9]{64})$/", http_get("param1")) == 1) {
        echo 'Deze evaluatielink is niet meer geldig..';
        die;
    }
      
    $oEvaluation = EvaluationManager::getEvaluationByLoginHash(http_get("param1"));
    if (empty($oEvaluation)) {
        http_redirect(getBaseUrl());
    }
    $oCustomer = CustomerManager::getCustomerById($oEvaluation->customerId);
    
    if (!empty($oCustomer)) {
        CustomerManager::updateLastLoginByCustomerId($oCustomer->customerId); //update last login date and time
        CustomerManager::setCustomerInSession($oCustomer); // set Customer in session     
    }  

}

// check if Customer is logged in
if (empty(Customer::getCurrent()) || !is_numeric(Customer::getCurrent()->customerId)) {
    http_redirect(getBaseUrl());
}

# action = save
if (http_post("action") == 'save') {

    # load data in object
    if ($_POST["loginHash"]) {

        if (!preg_match("/^([a-f0-9]{64})$/", $_POST["loginHash"]) == 1) {
            echo 'Deze evaluatielink is niet meer geldig..';
            die;
        }

        $oEvaluation = EvaluationManager::getEvaluationByLoginHash($_POST["loginHash"]);
        if (empty($oEvaluation)) {
            http_redirect(getBaseUrl());
        }
    }
    $oEvaluation->_load($_POST);
    $oEvaluation->dateSigned = date('Y-m-d', time());
    $oEvaluation->loginHash = '';
    
    # if object is valid, save
    if ($oEvaluation->isValid()) {
        
        $oEvaluation->digitalSigned = 1;

        EvaluationManager::saveEvaluation($oEvaluation); //save object
        $_SESSION['statusUpdate'] = sysTranslations::get('evaluation_saved'); //save status update into session

        saveLog(
            ADMIN_FOLDER . '/evaluaties/bewerken/' . $oEvaluation->evaluationId,
            ucfirst(http_get("param1")) . ' evaluatie ingevuld #' . $oEvaluation->evaluationId . ' (' . CustomerManager::getCustomerById($oEvaluation->customerId)->companyName . ')',
            arrayToReadableText($_POST)
          );
        
          http_redirect(getBaseUrl() . '/evaluation');
    } 
}

// get the current Customer's info from the database
$oCustomer = CustomerManager::getCustomerById(Customer::getCurrent()->customerId);

$oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
$oPageLayout->sMetaDescription = $oPage->getMetaDescription();
$oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
$oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
$oPageLayout->bIndexable = $oPage->isIndexable();

if ($oEvaluation) {
    $oPageLayout->sViewPath = getSiteView('evaluation_form', 'customers');
} else {
    $oPageLayout->sViewPath = getSiteView('evaluation_thanks', 'customers');

}

// template
include_once getSiteView('layout');