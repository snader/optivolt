<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('customer_groups');
$oPageLayout->sModuleName  = sysTranslations::get('customer_groups');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oCustomerGroup = CustomerGroupManager::getCustomerGroupById(http_get("param2"));
        if (empty($oCustomerGroup)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oCustomerGroup = new CustomerGroup();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oCustomerGroup->_load($_POST);

        # if object is valid, save
        if ($oCustomerGroup->isValid()) {
            CustomerGroupManager::saveCustomerGroup($oCustomerGroup); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_group_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCustomerGroup->customerGroupId);
        } else {
            Debug::logError("", "Customer group module php validate error", __FILE__, __LINE__, "Tried to save Customer group with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_group_not_saved');
        }
    }

    $aClients               = $oCustomerGroup->getClients(true);
    $oPageLayout->sViewPath = getAdminView('customerGroups/customerGroup_form', 'customers');
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oCustomerGroup = CustomerGroupManager::getCustomerGroupById(http_get("param2"));
        }

        # trigger error
        if (empty($oCustomerGroup)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_group_not_deleted'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
        }

        # get linked customers
        $aLinkedCustomers = $oCustomerGroup->getClients();
        if (!empty($aLinkedCustomers)) {
            # delete linked customers
            foreach ($aLinkedCustomers as $oGroupCustomer) {
                CustomerGroupManager::deleteCustomerFromCustomerGroup($oCustomerGroup->customerGroupId, $oGroupCustomer->customerId);
            }
        }

        if (!empty($oCustomerGroup) && CustomerGroupManager::deleteCustomerGroup($oCustomerGroup)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_group_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_group_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get("param1") == 'klanten-toevoegen' && is_numeric(http_get("param2"))) {

    # action = save
    if (http_post("action") == 'addMembersToCustomerGroup' && CSRFSynchronizerToken::validate()) {
        $aCustomerIds = http_post('aCustomerIds', []);
        foreach ($aCustomerIds as $iCustomerId) {
            CustomerManager::saveCustomerGroupRelation($iCustomerId, http_get('param2'));
        }
        if (!empty($aCustomerIds)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_added'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_not_added'); //save status update into session
        }
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/' . http_get('param1') . '/' . http_get('param2'));

    }

    # get all customer instances
    $aCustomers = CustomerManager::getAllCustomers();

    # get unique customerid's of all customers
    $aCustomersIds = [];
    foreach ($aCustomers as $oCustomers) {
        $aCustomersIds[] = $oCustomers->customerId;
    }

    # get current group
    $oCustomerGroup = CustomerGroupManager::getCustomerGroupById(http_get("param2"));

    # get clients of group
    $aCustomersOfGroup = $oCustomerGroup->getClients();

    # get unique customerid's of all customers
    $aCustomerIdsOfGroup = [];
    foreach ($aCustomersOfGroup as $oCustomerOfGroup) {
        $aCustomerIdsOfGroup[] = $oCustomerOfGroup->customerId;
    }

    # collect objects of customers not on this list
    $aCustomersNotInThisGroup = [];

    # compare customer id's to check the differences
    $aCustomerIdsNotInThisGroup = array_diff($aCustomersIds, $aCustomerIdsOfGroup);
    foreach ($aCustomerIdsNotInThisGroup as $iCustomerIdNotInThisGroup) {
        $oCustomer                  = CustomerManager::getCustomerById($iCustomerIdNotInThisGroup);
        $aCustomersNotInThisGroup[] = $oCustomer;
    }

    # load view
    $oPageLayout->sViewPath = getAdminView('customerGroups/customersGroup_addMembers', 'customers');
} elseif (http_get('param1') == 'ajax-checkName') {
    # check if name exists
    $oCustomerGroup = CustomerGroupManager::getCustomerGroupByName(http_get('name'));
    if ($oCustomerGroup && $oCustomerGroup->customerGroupId != http_get('customerGroupId')) {
        echo 'false';
    } else {
        echo 'true';
    }
    die;
} # display overview
else {
    $aCustomerGroups        = CustomerGroupManager::getAllCustomerGroups();
    $oPageLayout->sViewPath = getAdminView('customerGroups/customersGroup_overview', 'customers');
}

# include template
include_once getAdminView('layout');
