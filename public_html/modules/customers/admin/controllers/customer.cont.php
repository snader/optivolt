<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

error_reporting(E_ALL & ~E_NOTICE);

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('global_customers');
$oPageLayout->sModuleName  = sysTranslations::get('global_customers');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle customerFilter
$aCustomerFilter = http_session('customerFilter');
if (http_post('filterForm')) {
    $aCustomerFilter            = http_post('customerFilter');
    $_SESSION['customerFilter'] = $aCustomerFilter;
}

if (http_post('resetFilter') || empty($aCustomerFilter)) {
    unset($_SESSION['customerFilter']);
    $aCustomerFilter         = [];
    $aCustomerFilter['name'] = '';
}

# handle perPage
if (http_post('setPerPage')) {
    $_SESSION['customersPerPage'] = http_post('perPage');
}

# check if an email address exists
if (http_get('ajax') == 'checkEmail') {
    # return if the required fields are empty
    if (empty($_GET['email'])) {
        die(json_encode(null));
    }

    # return bool (true if email doesn't exists and vice versa)
    die(json_encode(!CustomerManager::emailExists(http_get('email'), http_get('customerId', null))));
}

# check if an email address exists
if (http_get('param1') == 'checkDebNr') {
    # return if the required fields are empty
    # check if username exists
    $oCustomer = CustomerManager::getCustomerByDebNr(http_post('debNr'));

    if ($oCustomer && $oCustomer->customerId != http_post('customerId')) {
        die('exists');
    } else {
        die('ok');
    }
}

if (http_get('undo-signature')) {

    if (trim(http_get('undo-signature'))!='')  {
        $aFilter['q'] = _e(http_get('undo-signature'));
        $oCustomer = CustomerManager::getCustomerById(http_get("param2"));
        $oAppointmentToUndo = AppointmentManager::getAppointmentsByFilter($aFilter);
        if ($oCustomer && $oAppointmentToUndo) {
            CustomerManager::undoSignature($aFilter['q']);
            $_SESSION['statusUpdate'] = 'Handtekening is verwijderd';
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCustomer->customerId);
        } 

       

    }

    $_SESSION['statusUpdate']['text'] = 'Handtekening kon niet worden verwijderd';
    $_SESSION['statusUpdate']['type'] = 'error';
    
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCustomer->customerId);

}

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oCustomer = CustomerManager::getCustomerById(http_get("param2"));
        if (empty($oCustomer)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oCustomer = new Customer();
    }

    if (http_get("param3") == 'afspraak-bekijken' && is_numeric(http_get("param4")) && $oCustomer->customerId) {
        $aAppointment = CustomerManager::getAppointmentById(http_get("param4"), $oCustomer->customerId, 'a');

        if (http_post("export") == 'PDF' && !empty($aAppointment)) {

            $oAppointment = CustomerManager::getAppointmentById($aAppointment['appointmentId'], $oCustomer->customerId, 'o');
            $oAppointment->getPdf()
                ->Output($oCustomer->debNr . '_' . prettyUrlPart($oCustomer->companyName) . '_onderhoud_' . date('Y', strtotime($oAppointment->visitDate)) . '_verwerkt.pdf', 'D');

        }

        if (http_post("mark") == 'mailed' && !empty($aAppointment)) {

            CustomerManager::saveMailed($aAppointment['userId'], $aAppointment['customerId'], $aAppointment['visitDate']);
            http_redirect(getCurrentUrl());
        }
        if (http_post("mark") == 'showCustomer' && !empty($aAppointment)) {

            CustomerManager::saveCustomerMark($aAppointment['userId'], $aAppointment['customerId'], $aAppointment['visitDate']);
            http_redirect(getCurrentUrl());
        }
        

    }

    if (http_get("param3") == 'afspraak-bewerken' && is_numeric(http_get("param4")) && $oCustomer->customerId) {
        $aEditAppointment = CustomerManager::getAppointmentById(http_get("param4"), $oCustomer->customerId);

    }
    if (http_get("param3") == 'afspraak-toevoegen' && $oCustomer->customerId) {
        $aEditAppointment = (array) new Appointment();
    }

    if (http_get("param3") =="afspraak-verwijderen" && is_numeric(http_get("param4")) && $oCustomer->customerId ) {
        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
            $aDeleteAppointment = CustomerManager::getAppointmentById(http_get("param4"), $oCustomer->customerId);
            if (!empty($aDeleteAppointment)) {
                CustomerManager::deleteAppointmentById(http_get("param4"), $oCustomer->customerId);

                saveLog(
                    getCurrentUrl(),
                    'Afspraak verwijderd klant #' . $oCustomer->customerId . ' (' . $oCustomer->companyName . ')',
                    arrayToReadableText($aDeleteAppointment)
                  );

            }
        }
    }


    if (http_post("saveAppointmentUserAndDate") == 'save' && CSRFSynchronizerToken::validate() && is_numeric(http_post('userId')) && http_post('visitDate')) {

        $aEditAppointment["visitDate"] = date('Y-m-d', strtotime(http_post('visitDate')));
        $aEditAppointment["userId"] = http_post('userId');
        $aEditAppointment["orderNr"] = http_post('orderNr');
        $aEditAppointment["customerId"] = $oCustomer->customerId;
        CustomerManager::saveAppointmentUserAndDate($aEditAppointment);

        saveLog(
            ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCustomer->customerId,
            'Afspraak opgeslagen klant #' . $oCustomer->customerId . ' (' . $oCustomer->companyName . ')',
            arrayToReadableText($aEditAppointment)
          );
        
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCustomer->customerId);
    }

    # action = saveAppointment
    if (http_post("saveAppointment") == 'save' && CSRFSynchronizerToken::validate() && is_numeric(http_post('userId')) && http_post('visitDate')) {

        $aPost["uitbreidingsmogelijkheden"] = http_post('uitbreidingsmogelijkheden');
        $aPost["vLiner"] = http_post('vLiner');
        $aPost["ml"] = http_post('ml');
        $aPost["koperenRailen"] = http_post('koperenRailen');
        $aPost["PQkast"] = http_post('PQkast');
        $aPost["onderhoudssticker"] = http_post('onderhoudssticker');
        $aPost["hoofdschakelaarTerug"] = http_post('hoofdschakelaarTerug');

        if (!empty(http_post('uitbrInfo'))) {
            $aPost["uitbrInfo"] = http_post('uitbrInfo');
        } else {
            $aPost["uitbrInfo"] = '';
        }

        saveLog(
            getCurrentUrl(),
            'Afspraak opgeslagen klant #' . $oCustomer->customerId . ' (' . $oCustomer->companyName . ')',
            arrayToReadableText($aPost)
          );

        CustomerManager::saveAppointment($aPost, http_post('userId'), $oCustomer->customerId, http_post('visitDate'));


        http_redirect(getCurrentUrl());
    }



    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # do special things before load (because of password hashing)
        if (http_get("param1") == 'bewerken') {
            if (empty($_POST["password"])) {
                #set password from object
                $_POST['password'] = $oCustomer->password;
            } else {

                #hash and check password for saving later
                if (!empty($_POST['password']) && strlen(http_post('password')) >= 8) {
                    $_POST['password'] = hashPasswordForDb(http_post('password'));
                } else {
                    $_POST['password'] = null;
                }
            }
        } else {
            # check password is valid
            if (!empty($_POST['password']) && strlen(http_post('password')) >= 8) {
                $_POST['password'] = hashPasswordForDb(http_post('password'));
            } else {
                $_POST['password'] = hashPasswordForDb(sha1($_POST['companyName'] . md5(rand(0,10000)) . $_POST['companyCity']));//null; //object validation will trigger error
            }
        }

        # load data in object
        $oCustomer->_load($_POST);

        if (empty($_POST['online'])) { $oCustomer->online = 0; }


        # add http when not already added
        $oCustomer->companyWebsite = addHttp($oCustomer->companyWebsite);

        $aCustomerGroups = [];
        foreach (http_post("customerGroupIds", []) AS $iCustomerGroupId) {
            $aCustomerGroups[] = new CustomerGroup(['customerGroupId' => $iCustomerGroupId]);
        }

        # set modules into customer
        $oCustomer->setCustomerGroups($aCustomerGroups);

        # if object is valid, save
        if ($oCustomer->isValid()) {

            CustomerManager::saveCustomer($oCustomer); //save object

            $_SESSION['statusUpdate'] = sysTranslations::get('customer_saved'); //save status update into session

            saveLog(
                getCurrentUrl(),
                'Klant opgeslagen #' . $oCustomer->customerId . ' (' . $oCustomer->companyName . ')',
                arrayToReadableText($_POST)
              );

            if (http_post("pl") && substr_count(http_post("pl"), '_') == 1) {
                http_redirect(ADMIN_FOLDER . '/planning/inplannen/' . http_post("pl") . '/' . $oCustomer->customerId);
            }

            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCustomer->customerId);
        } else {

            Debug::logError("", "Customer module php validate error", __FILE__, __LINE__, "Tried to save Customer with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_not_saved');
        }
    }

    // get last appointment
    if (UserManager::getCurrentUser()->userAccessGroupId == 2) {
        $aAppointment = CustomerManager::getLastAppointment(UserManager::getCurrentUser()->userId, $oCustomer->customerId);
    } else {
        $aAppointments = $oCustomer->getAppointments();
    }

    $oPageLayout->sViewPath = getAdminView('customers/customer_form', 'customers');
} elseif (http_get("param1") == 'systems') {

    if (http_get("param1") == 'systems' && is_numeric(http_get("param2"))) {
        $oCustomer = CustomerManager::getCustomerById(http_get("param2"));
        if (empty($oCustomer)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    }


    $aSystems = $oCustomer->getSystems();

    $oPageLayout->sViewPath = getAdminView('customers/customer_systems', 'customers');


}
elseif (http_get("param1")=='sigs' && !empty(http_get("param2"))) {


    $file = 'private_files/signatures/' . http_get("param2") . '.png';
    $type = 'image/png';
    header('Content-Type:' . $type);
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit();


}
#signature set
elseif (


    http_get("param1") == 'sig' &&
!empty(http_post('signature')) &&
is_numeric(http_post('userId')) &&
is_numeric(http_post('customerId'))
&& CSRFSynchronizerToken::validate()
) {


    $iUserId = http_post('userId');
    $iCustomerId = http_post('customerId');
    $sVisitDate = http_post('visitDate');
    $sSignature = http_post('signature');
    $sSignatureName = _e(http_post('signatureName'));


    $image_parts = explode(";base64,", $sSignature);
    $image_type_aux = explode("image/", $image_parts[0]);

    if (empty($image_type_aux) || !isset($image_type_aux[1])) {

        //$_SESSION['statusUpdate'] = 'De handtekening lijkt leeg te zijn. Probeer het nog eens.';
        $_SESSION['statusUpdate']['text'] = 'De handtekening lijkt leeg te zijn. Probeer het nog eens.';
        $_SESSION['statusUpdate']['type'] = 'error';

        http_redirect(ADMIN_FOLDER . "/klanten/bewerken/" . $iCustomerId);
    }

    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $sFilename = $iCustomerId . '_' . $iUserId . '_' . date('Y-m-d') . '_' . uniqid();

    $file = 'private_files/signatures/' .$sFilename . '.' . $image_type;

    file_put_contents($file, $image_base64);

    if (is_numeric($iUserId) && is_numeric($iCustomerId)) {
        CustomerManager::saveSignature($iUserId, $iCustomerId, $sVisitDate, $sFilename, $sSignatureName);

        saveLog(
            getCurrentUrl(),
            'Handtekening gezet #' . $iCustomerId . ' (' . CustomerManager::getCustomerById($iCustomerId)->companyName . ')',
            $file
          );


        http_redirect(ADMIN_FOLDER . "/klanten/bewerken/" . $iCustomerId);
    }

    http_redirect(ADMIN_FOLDER . "/");


}
# set object online/offline
elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline     = http_get("online", 0); //no value, set offline by default
    $bAjax       = http_get("ajax", false); //controller requested by ajax
    $iCustomerId = http_get("param2");
    $oResObj     = new stdClass(); //standard class for json feedback
    # update online for object
    if (is_numeric($iCustomerId)) {
        $oResObj->success    = CustomerManager::updateOnlineByCustomerId($bOnline, $iCustomerId);
        $oResObj->customerId = $iCustomerId;
        $oResObj->online     = $bOnline;
    }

    # redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '');
    }

    die(json_encode($oResObj));
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oCustomer = CustomerManager::getCustomerById(http_get("param2"));
        }

        if (!empty($oCustomer) && CustomerManager::deleteCustomer($oCustomer)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # display overview
else {

    if (http_post('action') == 'unlockCustomer') {
        if(!CSRFSynchronizerToken::validate()){
            die(json_encode(['status'=>false]));
        }
        $oCustomer = CustomerManager::getCustomerById(http_post('customerId'));
        if ($oCustomer->locked) {
            CustomerManager::unlockCustomer($oCustomer, http_post('unlockReason'));
        }
        http_redirect(getCurrentUrlPath());
    }


    if (!UserManager::getCurrentUser()->isClientAdmin() && !UserManager::getCurrentUser()->isSuperAdmin()) {
        // normal user
        $aCustomerFilter['userId'] = UserManager::getCurrentUser()->userId;
        //$aCustomerFilter['visitDate'] = date('Y-m-d', time());
        //$aCustomerFilter['finished'] = 1;
        $aCustomerFilter['online'] = true;
    }
    $aAllCustomers = CustomerManager::getCustomersByFilter($aCustomerFilter);

  
    $oPageLayout->sViewPath = getAdminView('customers/customers_overview', 'customers');
}

# include template
include_once getAdminView('layout');
?>