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
$oPageLayout->sWindowTitle = sysTranslations::get('inventarisation');
$oPageLayout->sModuleName  = sysTranslations::get('inventarisation');

# get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
Session::clear('statusUpdate'); //remove statusupdate, always show once

// handle perPage
if (Request::postVar('setPerPage')) {
    Session::set('inventarisationsPerPage', Request::postVar('perPage'));
}

// handle filter
$aInventarisationFilter = Session::get('inventarisationsFilter');
if (Request::postVar('filterInventarisations')) {
    $aInventarisationFilter            = Request::postVar('inventarisationsFilter');
    $aInventarisationFilter['showAll'] = true; // manually set showAll to true
    Session::set('inventarisationsFilter', $aInventarisationFilter);
   
}

if (Request::postVar('resetFilter') || empty($aInventarisationFilter)) {
    Session::clear('inventarisationsFilter');
    $aInventarisationFilter                       = [];
    $aInventarisationFilter['q']                  = '';
    $aInventarisationFilter['showAll']            = true; // manually set showAll to true
}

# handle add/edit
if (Request::param('ID') == 'bewerken' || Request::param('ID') == 'toevoegen') {

    # set crop referrer for pages module
    Session::set('cropReferrer', ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . Request::param('OtherID'));

    if (Request::param('ID') == 'bewerken' && is_numeric(Request::param('OtherID'))) {
        $oInventarisation = InventarisationManager::getInventarisationById(Request::param('OtherID'));
        if (!$oInventarisation) {
            Router::redirect(ADMIN_FOLDER . "/");
        }
        # is editable?
        if (!$oInventarisation->isEditable()) {
            Session::set('statusUpdate', sysTranslations::get('inventarisation_not_edited')); //save status update into session
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
        }
    
        # is editable?
        $aInventarisations = $oInventarisation->getSubInventarisations();             

    } else {
        // toevoegen
        $oInventarisation             = new Inventarisation();
        $oInventarisation->userId = UserManager::getCurrentUser()->userId;
        $aInventarisations[] = null;
    }

    # action = save
    if (Request::postVar("action") == 'save') {
               
        # parent inventarisation has only Id, remarks, userId and customerId
   
        $oInventarisation->remarks = _e(Request::postVar('remarks'));
        $oInventarisation->userId = UserManager::getCurrentUser()->userId;
        $oInventarisation->customerId = Request::postVar('customerId');
        $oInventarisation->customerName = _e(Request::postVar('customerName'));
        


        # if object is valid, save
        if ($oInventarisation->isValid()) {
            InventarisationManager::saveInventarisation($oInventarisation); //save item

            saveLog(
                ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oInventarisation->inventarisationId,
                ' Inventarisatie opgeslagen #' . $oInventarisation->inventarisationId . ' ',
                arrayToReadableText(object_to_array($oInventarisation))
              );

            // save extra rows of table one
            if (isset($_POST['inventarisationIdExtraTableOne'])) {

                foreach ($_POST['inventarisationIdExtraTableOne'] as $iKey => $iInventarisationId) {

                    if (empty($_POST['nameExtra'][$iKey]) && 
                        empty($_POST['kvaExtra'][$iKey]) &&
                        empty($_POST['loggerIdExtra'][$iKey]) && 
                        empty($_POST['positionExtra'][$iKey]) && 
                        empty($_POST['freeFieldAmpExtra'][$iKey]) && 
                        empty($_POST['stroomTrafoExtra'][$iKey]) 
                        ) {
                        continue;
                    }

                    if (!empty($_POST['inventarisationIdExtraTableOne'][$iKey])) {
                        $oSubInventarisation = InventarisationManager::getInventarisationById($_POST['inventarisationIdExtraTableOne'][$iKey]);
                    } else {
                        $oSubInventarisation = new Inventarisation();
                    }
                    
                    $oSubInventarisation->userId = UserManager::getCurrentUser()->userId;
                    if (!empty($iInventarisationId) && is_numeric($iInventarisationId)) {
                        $oSubInventarisation = InventarisationManager::getInventarisationById($iInventarisationId);
                    }

                    $oSubInventarisation->parentInventarisationId  = $oInventarisation->inventarisationId;
                    $oSubInventarisation->name = $_POST['nameExtra'][$iKey];
                    $oSubInventarisation->kva = $_POST['kvaExtra'][$iKey];
                    $oSubInventarisation->loggerId = $_POST['loggerIdExtra'][$iKey];
                    $oSubInventarisation->position = $_POST['positionExtra'][$iKey];
                    $oSubInventarisation->freeFieldAmp = $_POST['freeFieldAmpExtra'][$iKey];
                    $oSubInventarisation->stroomTrafo = $_POST['stroomTrafoExtra'][$iKey];
                    
                    // first table
                    if ($oSubInventarisation->isValid()) {                        
                        InventarisationManager::saveInventarisation($oSubInventarisation); //save subitem                            
                    } else {                        
                        die('Something went wrong..');
                    }                    
                    
                }
            }

            // save extra rows of table one
            if (isset($_POST['inventarisationIdExtraTableTwo'])) {

               

                foreach ($_POST['inventarisationIdExtraTableTwo'] as $iKey => $iInventarisationId) {

                    if (empty($_POST['typeExtra'][$iKey]) && 
                        empty($_POST['controlExtra'][$iKey]) &&
                        empty($_POST['relaisNrExtra'][$iKey]) && 
                        empty($_POST['engineKwExtra'][$iKey]) && 
                        empty($_POST['turningHoursExtra'][$iKey]) && 
                        empty($_POST['photoNrsExtra'][$iKey]) && 
                        empty($_POST['trafoNrExtra'][$iKey]) && 
                        empty($_POST['mlProposedExtra'][$iKey])
                        ) {
                        continue;
                    }

                    if (!empty($_POST['inventarisationIdExtraTableTwo'][$iKey])) {
                        $oSubInventarisation = InventarisationManager::getInventarisationById($_POST['inventarisationIdExtraTableTwo'][$iKey]);
                    
                    } else {
                       
                        $oSubInventarisation = new Inventarisation();                     
                    }

                    $oSubInventarisation->userId = UserManager::getCurrentUser()->userId;
                    if (!empty($iInventarisationId) && is_numeric($iInventarisationId)) {
                        $oSubInventarisation = InventarisationManager::getInventarisationById($iInventarisationId);
                    }

                    // second table
                    $oSubInventarisation->parentInventarisationId  = $oInventarisation->inventarisationId;
                    $oSubInventarisation->type = $_POST['typeExtra'][$iKey];
                    $oSubInventarisation->control = $_POST['controlExtra'][$iKey];                    
                    $oSubInventarisation->relaisNr = $_POST['relaisNrExtra'][$iKey];
                    $oSubInventarisation->engineKw = $_POST['engineKwExtra'][$iKey];
                    $oSubInventarisation->turningHours = $_POST['turningHoursExtra'][$iKey];
                    $oSubInventarisation->photoNrs = $_POST['photoNrsExtra'][$iKey];
                    $oSubInventarisation->trafoNr = $_POST['trafoNrExtra'][$iKey];
                    $oSubInventarisation->mlProposed = $_POST['mlProposedExtra'][$iKey];   
                    
                    

                    // first table
                    if ($oSubInventarisation->isValid()) {           
                        echo 'a';             
                        InventarisationManager::saveInventarisation($oSubInventarisation); //save subitem                            
                    } else {                        
                        die('Something went wrong..');
                    }       
                    
                }
            }

            /////////
            Session::set('statusUpdate', sysTranslations::get('inventarisation_saved')); //save status update into session

            if ($_POST["save"]==sysTranslations::get('global_save')) {
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oInventarisation->inventarisationId);
            } elseif (substr_count($_POST["save"], 'PDF')) {

                $sFilename = ($oInventarisation->customerName ? _e($oInventarisation->customerName) : CustomerManager::getCustomerById($oInventarisation->customerId)->companyName) . ' - ' . Usermanager::getUserById($oInventarisation->userId)->name . ' - ' . date('d-m-Y', strtotime($oInventarisation->created));

                $oInventarisation->getPdf()->Output('Inventarisation - ' . $sFilename . '.pdf', 'D');
            } else {
                Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
            }
            
            
        } else {
            Debug::logError("", "Inventarisation module php validate error", __FILE__, __LINE__, "Tried to save Inventarisation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('inventarisation_not_saved');
        }


       

    }

    $aFilter['showAll'] = true;
    $aLoggers = LoggerManager::getLoggersOnlyByFilter($aFilter);
    $aCustomers = CustomerManager::getAllCustomers();                                    
    
    $oPageLayout->sViewPath = getAdminView('inventarisations/inventarisation_form', 'inventarisations');

} elseif (Request::param('ID') == 'verwijderen' && is_numeric(Request::param('OtherID'))) {
    
        if (is_numeric(Request::param('OtherID'))) {
            $oInventarisation = InventarisationManager::getInventarisationById(Request::param('OtherID'));
        }
        if ($oInventarisation && InventarisationManager::deleteInventarisation($oInventarisation)) {

            saveLog(
                ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oInventarisation->inventarisationId,
                ' Inventarisatie gewist #' . $oInventarisation->inventarisationId . ' ',
                arrayToReadableText(object_to_array($oInventarisation))
              );

            Session::set('statusUpdate', sysTranslations::get('inventarisation_deleted')); //save status update into session
        } else {
            Session::set('statusUpdate', sysTranslations::get('inventarisation_not_deleted')); //save status update into session
        }
    
    Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());

} elseif (Request::param('ID') == 'deleterow' && $_POST['therowId']) {
    
        if (is_numeric($_POST['therowId'])) {
            $oInventarisation = InventarisationManager::getInventarisationById($_POST['therowId']);
        }
        if ($oInventarisation && InventarisationManager::deleteInventarisation($oInventarisation)) {
            die(1); // success return to ajax call
        } else {
            die();
        }
     
} else {

    $iNrOfRecords = DBConnection::count('inventarisations');
    $iPerPage     = Session::get('inventarisationsPerPage', 10);
    $iCurrPage    = Request::getVar('page') ? Request::getVar('page') : 1;

    if (Request::postVar('setPerPage')) {
        // reset iCurrPage on setPerPage change
        $iCurrPage = 1;
    }
    if ($iCurrPage > ($iNrOfRecords / $iPerPage) + 1) {
        // prevent non existing iCurrpage
        $iCurrPage = (round($iNrOfRecords / $iPerPage) + 1);
    }

    $iStart = (($iCurrPage - 1) * $iPerPage);
    if (!is_numeric($iCurrPage) || $iCurrPage <= 0) {
        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    }

    # add language to filter
    $aInventarisationFilter['isParent'] = 1;

    #display overview
    $aInventarisations      = InventarisationManager::getInventarisationsByFilter($aInventarisationFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount             = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;
    $oPageLayout->sViewPath = getAdminView('inventarisations/inventarisations_overview', 'inventarisations');
}

# include template
include_once getAdminView('layout');