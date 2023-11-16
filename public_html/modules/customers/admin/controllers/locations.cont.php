<?php
# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

# reset crop settings
Session::clear('aCropSettings');

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = "Systemen op locatie";
$oPageLayout->sModuleName  = "Systemen op locatie";

# get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");

Session::clear('statusUpdate'); //remove statusupdate, always show once


# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oLocation = LocationManager::getLocationById(http_get("param2"));
        if (empty($oLocation)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {

        if (is_numeric(http_get('customerId')) && CustomerManager::getCustomerById(http_get('customerId'))) {

           $oLocation = new Location();
           $oLocation->customerId = CustomerManager::getCustomerById(http_get('customerId'))->customerId;
        } else {
            http_redirect(ADMIN_FOLDER . "/");
        }
    }

    # action = save
    if (http_post("action") == 'save') {

        # load data in object
        $oLocation->_load($_POST);

        # if object is valid, save
        if ($oLocation->isValid()) {
            LocationManager::saveLocation($oLocation); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('location_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/klanten/bewerken/' . $oLocation->customerId);
        } else {

            Debug::logError("", "Location module php validate error", __FILE__, __LINE__, "Tried to save Location with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate']['text'] = sysTranslations::get('location_not_saved');
            $_SESSION['statusUpdate']['type'] = 'error';
        }
    }

    $aFilter['showAll'] = false;
    $bShowAddButton = false;
    if ($oCurrentUser->isClientAdmin() || $oCurrentUser->isSuperAdmin()) {
        $bShowAddButton = true;
        $aFilter['showAll'] = true;

        
    }


    $aSystems = $oLocation->getSystems($aFilter);


    $oPageLayout->sViewPath = getAdminView('locations/location_form', 'customers');
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {

        if (is_numeric(http_get("param2"))) {
            $oLocation = LocationManager::getLocationById(http_get("param2"));
        }

        # trigger error
        if (empty($oLocation)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('location_not_deleted'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
        }

        if (!empty($oLocation) && LocationManager::deleteLocation($oLocation)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('location_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('location_not_deleted'); //save status update into session
        }

    http_redirect(ADMIN_FOLDER . '/klanten/bewerken/' . $oLocation->customerId);
} # display overview
else {

}

# include template
include_once getAdminView('layout');
?>