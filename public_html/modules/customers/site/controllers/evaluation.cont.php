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

// Dit wordt een lange sha256 string op basis van evaluation id, customer id en created datum..
if (is_numeric(http_get("param1"))) {
    
    $oEvaluation = EvaluationManager::getEvaluationById(http_get("param1"));
    if (empty($oEvaluation) || $oEvaluation->customerId!=$oCustomer->customerId) {
        http_redirect(getBaseUrl());
    }

}


$oPageLayout->sViewPath = getSiteView('evaluation_detail', 'customers');

// template
include_once getSiteView('layout');