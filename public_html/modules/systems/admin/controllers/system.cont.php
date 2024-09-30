<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('system');
$oPageLayout->sModuleName  = sysTranslations::get('system');

# get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
Session::clear('statusUpdate'); //remove statusupdate, always show once

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



# handle add/edit
if (Request::param('ID') == 'bewerken' || Request::param('ID') == 'toevoegen') {

    # set crop referrer for pages module
    Session::set('cropReferrer', ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . Request::param('OtherID'));

    if (Request::param('ID') == 'bewerken' && is_numeric(Request::param('OtherID'))) {
        $oSystem = SystemManager::getSystemById(Request::param('OtherID'));
        if (!$oSystem) {
            Router::redirect(ADMIN_FOLDER . "/");
        }

        # is editable?
        //if (!$oSystem->isEditable()) {
            //Session::set('statusUpdate', sysTranslations::get('system_not_edited')); //save status update into session
            //Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
        //}

        $oLocation = $oSystem->getLocation();
        $oCustomer = $oLocation->getCustomer();

    } else {
        $oSystem             = new System();
        if (http_get('locationId') && is_numeric(http_get('locationId'))) {
            $oLocation = LocationManager::getLocationById(http_get('locationId'));
        }
        if (http_get('customerId') && is_numeric(http_get('customerId'))) {
            $oCustomer = CustomerManager::getCustomerById(http_get('customerId'));
        }

        if (isset($oLocation) && isset($oCustomer)) {
            if ($oLocation->customerId != $oCustomer->customerId) {
                Session::set('statusUpdate', sysTranslations::get('system_location_customer_error')); //save status update into session
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
            }
            $oSystem->locationId = $oLocation->locationId;
        } else {

            if (!$oCustomer) {
                Session::set('statusUpdate', sysTranslations::get('system_add_via_customer_error')); //save status update into session
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
            }

        }

    }

    # action = save
    if (Request::postVar("action") == 'save') {

        $bWasNietVervallen = $oSystem->online;
        
        # load data in object
        $oSystem->_load($_POST, false);

        $oSystem->notice = '';
        foreach ($_POST['notice'] as $sNotice) {
            if (trim(strip_tags($sNotice)) != '') {
                $oSystem->notice .= $sNotice . PHP_EOL;
            }
        }



        # if object is valid, save
        if ($oSystem->isValid()) {
            SystemManager::saveSystem($oSystem); //save item
            Session::set('statusUpdate', sysTranslations::get('system_saved')); //save status update into session

            if (!isset($oLocation) || empty($oLocation)) {
                $oLocation = $oSystem->getLocation();
                
            }

            if ($oLocation) {
                $oCustomer = $oLocation->getCustomer();
            }

            // mail naar optivolt met info (systeem is op vervallen gezet)
            if ($oSystem->online == false && $bWasNietVervallen) {
                // standaard info email adres uit settings
                $sEmail = Settings::get('infoEmail');
                $sFrom = $sEmail;
                $sSubject = "Systeem #" . $oSystem->systemId . " op vervallen gezet door " . UserManager::getCurrentUser()->name . ".";
                $sMailBody = $sSubject . '<br/>';
                $sMailBody .= "<br/>Klant: " . $oCustomer->companyName . " (#" . $oCustomer->debNr . ")";
                $sMailBody .= "<br/>Locatie: " . $oLocation->name;
                $sMailBody .= "<br/>Plaatsbepaling: " . $oLocation->floor;
               
                $sMailBody .= "<br/>" . sysTranslations::get('systems_type') . ": " . $oSystem->getSystemType()->name();
                $sMailBody .= "<br/>" . sysTranslations::get('systems_name') . ": " . $oSystem->name;
                $sMailBody .= "<br/>Positie: " . $oSystem->pos;
                $sMailBody .= "<br/>" . sysTranslations::get('systems_model') . ": " . $oSystem->model;
                if ($oSystem->systemTypeId == System::SYSTEM_TYPE_MULTILINER) {
                } else {
                    $sMailBody .= "<br/>Machine#: " . $oSystem->machineNr;
                }
                if (!empty($oSystem->installDate)) {
                    $sMailBody .= "<br/>Installatiedatum: " . date('d-m-Y', strtotime($oSystem->installDate));
                }
                $sMailBody .= "<br/>Opmerking vervallen: " . $_POST['notice'][0];
                $sLink = 'https://oms.optivolt.nl' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oSystem->systemId;            
                $sMailBody .= '<br/>Link: <a target="_blank" href="' . $sLink . '">' . $sLink . '</a>';
                
                // send it
                MailManager::sendMail($sEmail, $sSubject, $sMailBody, $sFrom);
               
            }                     

            saveLog(
                ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oSystem->systemId,
                ucfirst(http_get("param1")) . ' systeem #' . $oSystem->systemId . ' (' . $oSystem->name . ' - ' . $oLocation->getCustomer()->companyName . ' - ' . $oLocation->name . ')',
                arrayToReadableText(object_to_array($oSystem))
              );

            if ($_POST['save'] == 'Opslaan') {
                Router::redirect(ADMIN_FOLDER . '/systems/bewerken/' . $oSystem->systemId);
            }

            if ($_POST['save'] == 'Opslaan > locatie') {
                Router::redirect(ADMIN_FOLDER . '/locaties/bewerken/' . $oLocation->locationId);
            } else {
                Router::redirect(ADMIN_FOLDER . '/klanten/bewerken/' . $oLocation->getCustomer()->customerId);
            }
            //Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystem->systemId);
        } else {
            Debug::logError("", "System module php validate error", __FILE__, __LINE__, "Tried to save System with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('system_not_saved');
        }
    }

    $aSystemReports = SystemReportManager::getSystemReportsByFilter(['systemId' => $oSystem->systemId]);


    $oPageLayout->sViewPath = getAdminView('systems/system_form', 'systems');


} elseif (Request::param('ID') == 'verwijderen' && is_numeric(Request::param('OtherID'))) {
    if (CSRFSynchronizerToken::validate()) {
        if (is_numeric(Request::param('OtherID'))) {
            $oSystem = SystemManager::getSystemById(Request::param('OtherID'));
        }
        if ($oSystem && SystemManager::deleteSystem($oSystem)) {

            saveLog(
                ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oSystem->systemId,
                ucfirst(http_get("param1")) . ' systeem #' . $oSystem->systemId . ' (' . $oSystem->name . ' - ' . $oSystem->getLocation()->getCustomer()->companyName . ' - ' . $oSystem->getLocation()->name . ')',
                arrayToReadableText(object_to_array($oSystem))
              );

            Session::set('statusUpdate', sysTranslations::get('system_deleted')); //save status update into session
        } else {
            Session::set('statusUpdate', sysTranslations::get('system_not_deleted')); //save status update into session
        }
    }

    
    if (!empty(http_get("fromSystemsCustomer"))) {
        Router::redirect(ADMIN_FOLDER . '/klanten/systems/' . http_get("fromSystemsCustomer"));
    }

    if (!empty(http_get("fromLocation"))) {
        Router::redirect(ADMIN_FOLDER . '/locaties/bewerken/' . http_get("fromLocation"));
    }

    Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());

} elseif (Request::param('ID') == 'ajax-setOnline') {
    if (!CSRFSynchronizerToken::validate()) {
        die(json_encode(['success'=>false]));
    }
    $bOnline     = Request::getVar("online") ? Request::getVar("online") : 0; //no value, set offline by default
    $bAjax       = Request::getVar("ajax") ? Request::getVar("ajax") : false; //controller requested by ajax
    $iSystemId = Request::param('OtherID');
    $oResObj     = new stdClass(); //standard class for json feedback
    # update online for page
    if (is_numeric($iSystemId)) {
        $oResObj->success    = SystemManager::updateOnlineBySystemId($bOnline, $iSystemId);
        $oResObj->systemId = $iSystemId;
        $oResObj->online     = $bOnline;
    }

    if (!$bAjax) {
        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    }
    print json_encode($oResObj);
    die;
} else {

    #display overview
    $aCustomerFilter = [];
    if (!UserManager::getCurrentUser()->isClientAdmin() && !UserManager::getCurrentUser()->isSuperAdmin()) {
        // normal user
        $aCustomerFilter['userId'] = UserManager::getCurrentUser()->userId;
        //$aCustomerFilter['visitDate'] = date('Y-m-d', time());
        $aCustomerFilter['online'] = true;

        //$aSystemFilter['userId'] = UserManager::getCurrentUser()->userId;
        //$aSystemFilter['visitDate'] = date('Y-m-d', time());
        $aSystemFilter['online'] = true;
        $aSystemFilter['finished'] = 0;

    }
    $aAllCustomers = CustomerManager::getCustomersByFilter($aCustomerFilter);
    $aSystems               = SystemManager::getSystemsByFilter($aSystemFilter);


    $oPageLayout->sViewPath = getAdminView('systems/systems_overview', 'systems');
}

# include template
include_once getAdminView('layout');
