<?php

/*
 * controller for systemreports
 */

// make pageLayout Object
$oPageLayout = new PageLayout();

// get page by urlPath (/account/bewerken)
$oPage = PageManager::getPageByName('system_reports');

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


if (is_numeric(http_get("param1"))) {
    
    $oAppointment = CustomerManager::getAppointmentById(http_get("param1"), $oCustomer->customerId, 'o');
    if (empty($oAppointment)) {
        http_redirect(getBaseUrl());
    }

}

if (http_get('param2')=='pdf') {

    $oAppointment->getPdf()
                ->Output($oCustomer->debNr . '_' . prettyUrlPart($oCustomer->companyName) . '_onderhoud_' . date('Y', strtotime($oAppointment->visitDate)) . '_verwerkt.pdf', 'D');
    

} elseif (http_get('param2')=='detail') {

    $oPageLayout->sViewPath = getSiteView('ohform_detail', 'systems');

} else {

   $aFilter['customerId'] = $oCustomer->customerId;
   $aFilter['customer'] = 1; // alleen geactiveerde formulieren voor de klant
   $aAppointments = AppointmentManager::getAppointmentsByFilter($aFilter);
    
    /*$aSystems = $oCustomer->getSystems();
    $aSystemReports = [];

    foreach ($aSystems as $oSystem) {

        $aFilter['systemId'] = $oSystem->systemId;
        $aSystemReports = array_merge(SystemReportManager::getSystemReportsByFilter($aFilter));
    }
    */

    $oPageLayout->sViewPath = getSiteView('ohforms_overview', 'systems');

}

// template
include_once getSiteView('layout');