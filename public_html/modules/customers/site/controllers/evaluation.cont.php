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

// get the current Customer's info from the database
$oCustomer = CustomerManager::getCustomerById(Customer::getCurrent()->customerId);

$oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
$oPageLayout->sMetaDescription = $oPage->getMetaDescription();
$oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
$oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
$oPageLayout->bIndexable = $oPage->isIndexable();



$oPageLayout->sViewPath = getSiteView('evaluation_detail', 'customers');

// template
include_once getSiteView('layout');