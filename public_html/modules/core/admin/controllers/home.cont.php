<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

// no rights for home? send to first item in menu
if (!$oCurrentUser->hasRightsForModule('')) {
    $aMenuModules = $oCurrentUser->getUserAccessGroup()
        ->getModules();
    if (!empty($aMenuModules[0])) {
        http_redirect(ADMIN_FOLDER . '/' . $aMenuModules[0]->name);
    } else {
        return Router::httpError(404);
    }
}

if (UserManager::getCurrentUser()->userAccessGroupId == 2) {

    // handle filter
    $aSystemFilter = Session::get('systemFilter');
    if (Request::postVar('filterSystems')) {

        $aSystemFilter            = Request::postVar('systemFilter');
        $aSystemFilter['showAll'] = true; // manually set showAll to true
        Session::set('systemFilter', $aSystemFilter);
    }

    if (Request::postVar('resetFilter') || empty($aSystemFilter)) {
        Session::clear('systemFilter');
        $aSystemFilter                       = [];
        $aSystemFilter['q']                  = '';
        $aSystemFilter['showAll']            = true; // manually set showAll to true
    }

    if (Request::getVar('customerId') && is_numeric(Request::getVar('customerId'))) {
        $aSystemFilter['customerId'] = Request::getVar('customerId');
        Session::set('systemFilter', $aSystemFilter);
    }

    if (isset($aSystemFilter['customerId']) && !is_numeric($aSystemFilter['customerId'])) {
        unset($aSystemFilter['customerId']);
    }

    #display overview
    $aCustomerFilter = [];
    $aCustomerFilter['userId'] = UserManager::getCurrentUser()->userId;
    //$aCustomerFilter['visitDate'] = date('Y-m-d', time());
    $aCustomerFilter['fromVisitDate'] = date('Y-m-d', strtotime("-2 day"));
    $aCustomerFilter['online'] = true;
    $aCustomerFilter['joinSystems'] = true;

    $aAllCustomers = CustomerManager::getCustomersByFilter($aCustomerFilter);



} else {

    // handle filter
    $aDashboardFilter = Session::get('dashboardFilter');
    if (Request::postVar('filterDashboard')) {
        $aDashboardFilter            = Request::postVar('dashboardFilter');
        $aDashboardFilter['showAll'] = true; // manually set showAll to true
        Session::set('dashboardFilter', $aDashboardFilter);
    }

    if (!isset($aDashboardFilter['dates']) || empty($aDashboardFilter['dates'])) {
        $aDashboardFilter['dates'] = date('d-m-Y', strtotime("-10 day")) . ' - ' . date('d-m-Y', strtotime("+20 day"));
    }

    //[dates] => 11/01/2021 - 12/22/2021
    if (!empty($aDashboardFilter['dates'])) {
        $aDates = explode(' - ', $aDashboardFilter['dates']);
        $aDashboardFilter['startDate'] = date('Y-m-d', strtotime($aDates[0]));
        $aDashboardFilter['endDate'] = date('Y-m-d', strtotime($aDates[1]));
    }

//    $aDashboardFilter['fromVisitDate'] = date('Y-m-d', strtotime("-15 day"));
    $aDashboardFilter['online'] = true;
    $aDashboardFilter['joinSystems'] = true;
    $aDashboardFilter['allUsers'] = true;

    $aAllCustomers = CustomerManager::getCustomersByFilter($aDashboardFilter);

}

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('global_home');
$oPageLayout->sModuleName  = sysTranslations::get('global_home');

$oPageLayout->sViewPath = getAdminView('home/home');

include_once getAdminView('layout');
?>