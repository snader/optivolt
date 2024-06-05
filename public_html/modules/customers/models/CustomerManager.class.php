<?php

class CustomerManager
{

    const SESSION = 'oCurrentCustomer';

    /**
     * get a Customer by id
     *
     * @param int $iCustomerId
     *
     * @return Customer
     */
    public static function getCustomerById($iCustomerId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `customers`
                    WHERE
                        `customerId` = ' . db_int($iCustomerId) . ' AND `deleted` = ' . db_int(0) . '
                    LIMIT 1
                    ;';


        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Customer");
    }

    /**
     * get a Customer by email
     *
     * @param string $sEmail
     *
     * @return Customer
     */
    public static function getCustomerByEmail($sEmail)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `customers`
                    WHERE
                        `companyEmail` = ' . db_str($sEmail) . '  AND `deleted` = ' . db_int(0) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Customer");
    }


    /**
     * get a Customer by debNr
     *
     * @param string $sEmail
     *
     * @return Customer
     */
    public static function getCustomerByDebNr($sDebNr)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `customers`
                    WHERE
                        `debNr` = ' . db_str($sDebNr) . ' AND `deleted` = ' . db_int(0) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Customer");
    }

    /**
     * get a Customer by debNr & Email
     *
     * @param string $sDebNr
     * @param string $sEmail
     *
     * @return Customer
     */
    public static function getCustomerByEmailDebNr($sDebNr, $sEmail) 
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `customers`
                    WHERE
                        `contactPersonEmail` = ' . db_str($sEmail) . ' AND 
                        `debNr` = ' . db_str($sDebNr) . '  AND `deleted` = ' . db_int(0) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Customer");
    }

    /**
     * get a Customer by email
     *
     * @param string $sEmail
     *
     * @return Customer
     */
    public static function getCustomerByFields($sCompanyName, $sCompanyAddress)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `customers`
                    WHERE
                        `companyName` = ' . db_str($sCompanyName) . ' AND
                        `companyAddress` = ' . db_str($sCompanyAddress) . '  AND `deleted` = ' . db_int(0) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Customer");
    }



    /**
     * get customers by customerGroupId
     *
     * @param int     $iCustomerGroupId
     * @param boolean $bFilterOnline
     * @param boolean $bExcludeBounced
     * @param boolean $bFilterBounced
     *
     * @return Customer
     */
    public static function getCustomersByCustomerGroupId($iCustomerGroupId, $bFilterOnline = false)
    {

        $sQuery = ' SELECT
                        *
                    FROM
                        `customer_group_relations` as `cgr`
                    JOIN
                        `customers` as `c`
                    USING
                        (`customerId`)
                    WHERE
                        `cgr`.`customerGroupId` = ' . db_int($iCustomerGroupId);

        # filter online property
        if ($bFilterOnline) {
            $sQuery .= ' AND `c`.`online` = 1 ';
        }

        $sQuery .= ';';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "Customer");
    }

    /**
     * get amount of customers by customerGroupId
     *
     * @param int     $iCustomerGroupId
     * @param boolean $bFilterOnline
     * @param boolean $bFilterBounced
     *
     * @return Customer
     */
    public static function getAmountOfCustomersByCustomerGroupId($iCustomerGroupId, $bFilterOnline = false)
    {

        $sQuery = ' SELECT
                        COUNT(*) as `amount`
                    FROM
                        `customer_group_relations` as `cgr`
                    JOIN
                        `customers` as `c`
                    USING
                        (`customerId`)
                    WHERE
                        `cgr`.`customerGroupId` = ' . db_int($iCustomerGroupId) . '  AND `c`.`deleted` = ' . db_int(0);

        # filter online property
        if ($bFilterOnline) {
            $sQuery .= ' AND `c`.`online` = 1 ';
        }

        $sQuery             .= ';';
        $oDb                = DBConnections::get();
        $aResult            = $oDb->query($sQuery, QRY_UNIQUE_ARRAY);
        $iAmountOfCustomers = $aResult['amount'];

        return $iAmountOfCustomers;
    }

    /**
     * get a Customer by confirmCode and Email
     *
     * @param string $sConfirmCode
     * @param string $sEmail
     *
     * @return Customer
     */
    public static function getCustomerByConfirmCodeAndEmail($sConfirmCode, $sEmail)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `customers`
                    WHERE
                        `confirmCode` = ' . db_str($sConfirmCode) . '
                    AND
                        `contactPersonEmail` = ' . db_str($sEmail) . ' AND `c`.`deleted` = ' . db_int(0) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Customer");
    }

    /**
     * get all Customer objects
     *
     * @return array Customer
     */
    public static function getAllCustomers()
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `customers`
                    WHERE `deleted`= ' . db_int(0) . '
                    ORDER BY
                        `companyName` ASC 
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "Customer");
    }

    /**
     *
     */
    public static function getLastAppointment($iUserId = null, $iCustomerId)
    {

        if ($iUserId) {
            $sQuery =
                ' SELECT
                            *
                        FROM
                            `users_customers`
                        WHERE
                            `userId` = ' . db_int($iUserId) . ' AND `customerId` = ' . db_int($iCustomerId) .
                ' ORDER BY `visitDate` DESC LIMIT 0,1
            ';
        } else {
            $sQuery = ' SELECT
                        *
                    FROM
                        `users_customers`
                    WHERE
                        `customerId` = ' . db_int($iCustomerId) . ' ORDER BY `visitDate` DESC LIMIT 0,1
        ';
        }

        $oDb = DBConnections::get();
        return $oDb->query($sQuery, QRY_UNIQUE_ARRAY);

    }

    public static function getAppointmentByData($iUserId, $iCustomerId, $sVisitDate)
    {
        $sQuery =
            ' SELECT
                        *
                    FROM
                        `users_customers`
                    WHERE
                `userId` = ' . db_int($iUserId) . ' AND `customerId` = ' . db_int($iCustomerId) . ' AND `visitDate` = ' . db_str($sVisitDate) . '
         LIMIT 0,1
        ';
        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Appointment");
    }


    public static function getAppointmentById($iAppointmentId, $iCustomerId, $sObjectOrArray = 'a') {
        $sQuery = ' SELECT
                        *
                    FROM
                        `users_customers`
                    WHERE
                        `appointmentId` = ' . db_int($iAppointmentId) . ' AND `customerId` = ' . db_int($iCustomerId) . ' LIMIT 0,1
        ';
        $oDb = DBConnections::get();
        if ($sObjectOrArray == 'a') {
            return $oDb->query($sQuery, QRY_UNIQUE_ARRAY);
        } else {
            return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Appointment");
        }
    }

    public static function deleteAppointmentById($iAppointmentId, $iCustomerId)
    {
        $sQuery = 'DELETE FROM `users_customers`
                    WHERE
                        `appointmentId` = ' . db_int($iAppointmentId) . ' AND `customerId` = ' . db_int($iCustomerId);
        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     *
     */
    public static function getAppointmentsByCustomerId($iCustomerId) {

        $sQuery =
        ' SELECT
                        `uc`.*,
                        `u`.`name`
                    FROM
                        `users_customers` as `uc`
                    LEFT JOIN `users` AS `u` ON `u`.`userId` = `uc`.`userId`
                    WHERE
                        `uc`.`customerId` = ' . db_int($iCustomerId) . ' ORDER BY `uc`.`visitDate` DESC LIMIT 0,10
        ';

        $oDb = DBConnections::get();
        return $oDb->query($sQuery, QRY_OBJECT);

    }

    /**
     *
     */
    public static function saveAppointment($aPost,$iUserId, $iCustomerId, $sVisitDate) {

        $oAppointment = CustomerManager::getAppointmentByData($iUserId, $iCustomerId, $sVisitDate);

        $sQuery = '
            UPDATE `users_customers`
            SET
                `uitbreidingsmogelijkheden` = ' . db_str($aPost['uitbreidingsmogelijkheden']) . ',
                `uitbrInfo` = ' . db_str($aPost['uitbrInfo']) . ',
                `vLiner` = ' . db_str($aPost['vLiner']) . ',
                `ml` = ' . db_str($aPost['ml']) . ',
                `koperenRailen` = ' . db_str($aPost['koperenRailen']) . ',
                `PQkast` = ' . db_str($aPost['PQkast']) . ',
                `onderhoudssticker` = ' . db_str($aPost['onderhoudssticker']) . ',
                `hoofdschakelaarTerug` = ' . db_str($aPost['hoofdschakelaarTerug']) . '
            WHERE
                `userId` = ' . db_int($iUserId) . ' AND `customerId` = ' . db_int($iCustomerId) . ' AND `visitDate` = ' . db_str($sVisitDate) . '
        '; //  AND `finished` = 0

        //die($oAppointment->uitbrInfo . ' - ' . $aPost['uitbrInfo']);

        if (trim($aPost['uitbrInfo']) != '' && ($oAppointment->uitbrInfo != $aPost['uitbrInfo'])) {
            //_d($sQuery);
            $oSetting = SettingManager::getSettingByName('infoEmail');
            if ($oSetting) {

                $oCustomer = CustomerManager::getCustomerById($iCustomerId);

                $sLink = 'https://oms.optivolt.nl/dashboard/klanten/bewerken/' . $iCustomerId . '/afspraak-bekijken/' . $oAppointment->appointmentId;

                $sSubject = 'Wijziging voor uitbreidingsmogelijkheden ' . $oCustomer->companyName;

                $sBody = '<!DOCTYPE html><html><body>';
                $sBody .= $sSubject . '<br/><br/>Opmerking:<br/><br/>' . _e($aPost['uitbrInfo']);
                $sBody .= '<br/><br/>Zie details op: <a target="_blank" href="' . $sLink . '">' . $sLink . '</a>';
                $sBody .= '</body></html>';

                //
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                $headers[] = 'From: info@optivolt.nl';
                $headers[] = 'Reply-To: info@optivolt.nl' .
                $headers[] = 'X-Mailer: PHP/' . phpversion();

                //$oSetting->value = "sander.voorn@gmail.com";

                mail(
                    $oSetting->value,
                    $sSubject,
                    $sBody,
                    implode("\r\n", $headers)

                );
                //mail($to, $subject, $message, implode("\r\n", $headers));
                //MailManager::sendMail($oSetting->value, $sSubject, _e($aPost['uitbrInfo']));

                //echo $oSetting->value;
                //echo $sSubject;
                //die($sBody);
            }
        }
        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

    }

    /**
     *
     */
    public static function saveAppointmentUserAndDate($aEditAppointment) {
            $sQuery = ' INSERT INTO `users_customers`(
                        `appointmentId`,
                        `userId`,
                        `customerId`,
                        `orderNr`,
                        `visitDate`
                    )
                    VALUES (
                        ' . db_int($aEditAppointment["appointmentId"]) . ',
                        ' . db_int($aEditAppointment["userId"]) . ',
                        ' . db_int($aEditAppointment["customerId"]) . ',
                        ' . db_str($aEditAppointment["orderNr"]) . ',
                        ' . db_str($aEditAppointment["visitDate"]) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `appointmentId`=VALUES(`appointmentId`),
                        `userId`=VALUES(`userId`),
                        `customerId`=VALUES(`customerId`),
                        `orderNr`=VALUES(`orderNr`),
                        `visitDate`=VALUES(`visitDate`)
                    ;';



            $oDb = DBConnections::get();
            $oDb->query($sQuery, QRY_NORESULT);

    }


    public static function undoSignature($sSignature) {
        $sQuery =
        '
        UPDATE `users_customers`
        SET
            `finished` = 0,
            `signature` = ' . db_str('') . ',
            `signatureName` = ' . db_str('') . '
        WHERE
            `signature` = ' . db_str($sSignature) . ' AND `finished` = 1
    ';

    $oDb = DBConnections::get();
    $oDb->query($sQuery, QRY_NORESULT);

    }

    /**
     *
     */
    public static function saveSignature($iUserId, $iCustomerId, $sVisitDate, $sSignature, $sSignatureName = null)
    {

        $sQuery =
            '
            UPDATE `users_customers`
            SET
                `finished` = 1,
                `signature` = ' . db_str($sSignature) . ',
                `signatureName` = ' . db_str($sSignatureName) . '
            WHERE
                `userId` = ' . db_int($iUserId) . ' AND `customerId` = ' . db_int($iCustomerId) . ' AND `visitDate` = ' . db_str($sVisitDate) . ' AND `finished` = 0
        ';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }


    public static function saveMailed($iUserId, $iCustomerId, $sVisitDate)
    {

        $sQuery = '
            UPDATE `users_customers`
            SET
                `mailed` = 1
            WHERE
                `userId` = ' . db_int($iUserId) . ' AND `customerId` = ' . db_int($iCustomerId) . ' AND `visitDate` = ' . db_str($sVisitDate) . ' AND `finished` = 1
        ';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    public static function saveCustomerMark($iUserId, $iCustomerId, $sVisitDate)
    {

        $sQuery = '
            UPDATE `users_customers`
            SET
                `customer` = 1
            WHERE
                `userId` = ' . db_int($iUserId) . ' AND `customerId` = ' . db_int($iCustomerId) . ' AND `visitDate` = ' . db_str($sVisitDate) . ' AND `finished` = 1
        ';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }


    /**
     * return customers filtered by a few options
     *
     * @param array $aFilter    filter properties (q)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Customer objects
     */
    public static function getCustomersByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`c`.`companyName`' => 'ASC', '`c`.`companyCity`' => 'ASC'])
    {

        $sSelect = '';
        $sWhere = '';
        $sFrom  = '';
        $sGroupBy = '';

        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
        `c`.`deleted` = 0
    ';



        # search for q
        if (!empty($aFilter['name'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`c`.`companyAddress` LIKE ' . db_str('%' . $aFilter['name'] . '%') . ' OR `c`.`companyName` LIKE ' . db_str('%' . $aFilter['contactPersonName'] . '%') . ' OR `c`.`companyCity` LIKE ' . db_str(
                    '%' . $aFilter['name'] . '%'
                ) . ')';
        }

        # check online yes/no
        if (!empty($aFilter['online'])) {
            if ($aFilter['online'] === true) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`c`.`online` = 1 ';
            } elseif ($aFilter['online'] === false) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`c`.`online` = 0 ';
            }
        }

        if (!empty($aFilter['userId'])) {

            $sFrom  .= ' JOIN `users_customers` AS `uc` ON `uc`.`customerId` = `c`.`customerId`' . PHP_EOL;
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`uc`.`userId` = ' . db_int($aFilter['userId']);

            if (!empty($aFilter['visitDate'])) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`uc`.`visitDate` = ' . db_str($aFilter['visitDate']);
            }

            if (!empty($aFilter['fromVisitDate'])) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`uc`.`visitDate` >= ' . db_str($aFilter['fromVisitDate']) . ' OR `uc`.`finished` = 0)';
            }
            $sGroupBy = ' GROUP BY `c`.`customerId`' . PHP_EOL;

        }

        if (!empty($aFilter['allUsers'])) {
            $sFrom  .= ' JOIN `users_customers` AS `uc` ON `uc`.`customerId` = `c`.`customerId`' . PHP_EOL;
            $sGroupBy = ' GROUP BY `c`.`customerId`' . PHP_EOL;
        }

        if (isset($aFilter['joinSystems']) && $aFilter['joinSystems'] === true) {

            $sSelect = ',
                        `uc`.*,
                        COUNT(`l`.`name`) as `locations` ' . PHP_EOL;
            $sFrom  .= ' LEFT JOIN `locations` AS `l` ON `l`.`customerId` = `c`.`customerId`' . PHP_EOL;
            $sGroupBy = ' GROUP BY `c`.`customerId`' . PHP_EOL;

            $aOrderBy = ['`uc`.`visitDate`' => 'ASC', '`c`.`companyName`' => 'ASC', '`c`.`companyCity`' => 'ASC'];
            //$sFrom  .= ' LEFT JOIN `systems` AS `s` ON `s`.`locationId` = `c`.`customerId`';
           // $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`l`.`customerId` = ' . db_int($aFilter['userId']);

        }

        if (!empty($aFilter['startDate']) && !empty($aFilter['endDate'])) {

            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`uc`.`visitDate` >= ' . db_str($aFilter['startDate']) . ' AND `uc`.`visitDate` <= ' . db_str($aFilter['endDate']) . ')';

        };

        # handle order by
        $sOrderBy = '';
        if (count($aOrderBy) > 0) {
            foreach ($aOrderBy AS $sColumn => $sOrder) {
                $sOrderBy .= ($sOrderBy !== '' ? ',' : '') . $sColumn . ' ' . $sOrder;
            }
        }
        $sOrderBy = ($sOrderBy !== '' ? 'ORDER BY ' : '') . $sOrderBy;

        # handle start,limit
        $sLimit = '';
        if (is_numeric($iLimit)) {
            $sLimit .= db_int($iLimit);
        }
        if ($sLimit !== '') {
            $sLimit = (is_numeric($iStart) ? db_int($iStart) . ',' : '0,') . $sLimit;
        }
        $sLimit = ($sLimit !== '' ? 'LIMIT ' : '') . $sLimit;

        $sQuery = ' SELECT ' . ($iFoundRows !== false ? 'SQL_CALC_FOUND_ROWS' : '') . '
                        `c`.*
                        ' . $sSelect . '
                    FROM
                        `customers` AS `c`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sGroupBy . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb       = DBConnections::get();
        $aProducts = $oDb->query($sQuery, QRY_OBJECT, "Customer");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aProducts;
    }

    /**
     * check if the email address exists
     *
     * @param string $sEmail
     * @param int    $iCustomerId
     *
     * @return bool
     */
    public static function emailExists($sEmail, $iCustomerId = null)
    {
        $oCustomer = self::getCustomerByEmail($sEmail);
        if (!empty($oCustomer)) {
            if ($iCustomerId === null || $oCustomer->customerId != $iCustomerId) {
                return true;
            }
        }

        return false;
    }

    /**
     * check if the debiteurennummer exists
     *
     * @param string $sDebNr
     * @param int    $iCustomerId
     *
     * @return bool
     */
    public static function dbNrExists($sDebNr, $iCustomerId = null)
    {
        $oCustomer = self::getCustomerByDebNr($sDebNr);
        if (!empty($oCustomer)) {
            if ($iCustomerId === null || $oCustomer->customerId != $iCustomerId) {
                return true;
            }
        }

        return false;
    }


    /**
     * save a Customer
     *
     * @param Customer $oCustomer
     * @param bool     $bAddUserToGeneralCustomerAccount
     */
    public static function saveCustomer(Customer $oCustomer, $bAddUserToGeneralCustomerAccount = true)
    {
        $sQuery = ' INSERT INTO `customers`(
                        `customerId`,
                        `debNr`,
                        `companyName`,
                        `companyAddress`,
                        `companyPostalCode`,
                        `companyCity`,
                        `companyEmail`,
                        `companyPhone`,
                        `companyWebsite`,
                        `contactPersonName`,
                        `contactPersonEmail`,
                        `contactPersonPhone`,
                        `password`,
                        `confirmCode`,
                        `online`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oCustomer->customerId) . ',
                        ' . db_str($oCustomer->debNr) . ',
                        ' . db_str($oCustomer->companyName) . ',
                        ' . db_str($oCustomer->companyAddress) . ',
                        ' . db_str($oCustomer->companyPostalCode) . ',
                        ' . db_str($oCustomer->companyCity) . ',
                        ' . db_str($oCustomer->companyEmail) . ',
                        ' . db_str($oCustomer->companyPhone) . ',
                        ' . db_str($oCustomer->companyWebsite) . ',
                        ' . db_str($oCustomer->contactPersonName) . ',
                        ' . db_str($oCustomer->contactPersonEmail) . ',
                        ' . db_str($oCustomer->contactPersonPhone) . ',
                        ' . db_str($oCustomer->password) . ',
                        ' . db_str($oCustomer->confirmCode) . ',
                        ' . db_int($oCustomer->online) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `debNr`=VALUES(`debNr`),
                        `companyName`=VALUES(`companyName`),
                        `companyAddress`=VALUES(`companyAddress`),
                        `companyPostalCode`=VALUES(`companyPostalCode`),
                        `companyCity`=VALUES(`companyCity`),
                        `companyEmail`=VALUES(`companyEmail`),
                        `companyPhone`=VALUES(`companyPhone`),
                        `companyWebsite`=VALUES(`companyWebsite`),
                        `contactPersonName`=VALUES(`contactPersonName`),
                        `contactPersonEmail`=VALUES(`contactPersonEmail`),
                        `contactPersonPhone`=VALUES(`contactPersonPhone`),
                        `password`=VALUES(`password`),
                        `confirmCode`=VALUES(`confirmCode`),
                        `online`=VALUES(`online`)
                    ;';



        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oCustomer->customerId === null) {
            $oCustomer->customerId = $oDb->insert_id;

            # by default, set customer in general group
            if ($bAddUserToGeneralCustomerAccount) {
                $oCustomer->setCustomerGroups(array_merge([CustomerGroupManager::getCustomerGroupByName(CustomerGroup::CUSTOMERGROUP_GENERAL)], $oCustomer->getCustomerGroups()));
            }
        }

        self::saveCustomerGroups($oCustomer);
    }

    /**
     * Save the customerGroup relations of a customer
     *
     * @param CustomerGroup object
     */
    private static function saveCustomerGroups(Customer $oCustomer)
    {
        $aCustomerGroups = $oCustomer->getCustomerGroups();

        // Delete all customerGroup relations of this customer
        $sQuery = "DELETE FROM `customer_group_relations` WHERE `customerId` = " . db_int($oCustomer->customerId);
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        // Insert all customerGroup relations of this customer
        $sQueryValues = '';
        foreach ($aCustomerGroups AS $oCustomerGroup) {
            $sQueryValues .= (!empty($sQueryValues) ? ',' : '') . '(' . db_int($oCustomerGroup->customerGroupId) . ',' . db_int($oCustomer->customerId) . ')';
        }

        /* save User Module relation */
        if (!empty($sQueryValues)) {
            $sQuery = " INSERT IGNORE INTO
                            `customer_group_relations`
                        (
                            `customerGroupId`,
                            `customerId`
                        )
                        VALUES " . $sQueryValues . "
                        ;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }



    /**
     * Save the customerGroup relations of a customer
     *
     * @param int $iCustomerId
     * @param int $iCustomerGroupId
     */
    public static function saveCustomerGroupRelation($iCustomerId, $iCustomerGroupId)
    {

        $sQuery = " INSERT IGNORE INTO
                        `customer_group_relations`
                    (
                        `customerGroupId`,
                        `customerId`
                    )
                    VALUES (
                        " . db_int($iCustomerGroupId) . ",
                        " . db_int($iCustomerId) . "
                    )
                    ;";
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * update online status of Customer by id
     *
     * @param int $bOnline
     * @param int $iCustomerId
     *
     * @return bool
     */
    public static function updateOnlineByCustomerId($bOnline, $iCustomerId)
    {
        $sQuery = ' UPDATE
                        `customers`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `customerId` = ' . db_int($iCustomerId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }


    /**
     * confirm a Customer by confirmCode and email (make him online)
     *
     * @param string $sConfirmCode
     * @param string $sEmail
     *
     * @return bool
     */
    public static function confirmCustomerByConfirmCodeAndEmail($sConfirmCode, $sEmail)
    {
        # get the Customer by confirmCode
        $oCustomer = self::getCustomerByConfirmCodeAndEmail($sConfirmCode, $sEmail);

        # check if Customer exists
        if (empty($oCustomer)) {
            return false;
        } else {
            # update the Customer
            $sQuery = ' UPDATE
                            `customers`
                        SET
                            `confirmCode` = NULL,
                            `online` = 1
                        WHERE
                            `customerId` = ' . db_int($oCustomer->customerId) . '
                        LIMIT 1
                        ;';

            $oDb = DBConnections::get();
            $oDb->query($sQuery, QRY_NORESULT);

            self::updateLastLoginByCustomerId($oCustomer->customerId); //update last login date and time
            self::setCustomerInSession($oCustomer); // set Customer in session
            # check if something happened
            return $oDb->affected_rows > 0;
        }
    }

    /**
     * delete a Customer
     *
     * @param Customer $oCustomer
     *
     * @return bool true
     */
    public static function deleteCustomer(Customer $oCustomer)
    {
        /*$sQuery = ' DELETE FROM
                        `customers`
                    WHERE
                        `customerId` = ' . db_int($oCustomer->customerId) . '
                    LIMIT 1
                    ;';
*/

        $sQuery = "UPDATE `customers` SET `deleted` = 1 WHERE `customerId` = " . db_int($oCustomer->customerId) . ";";

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * update last login timestamp and reset the confirmCode
     *
     * @param int $iCustomerId
     */
    public static function updateLastLoginByCustomerId($iCustomerId)
    {
        $sQuery = ' UPDATE
                        `customers`
                    SET
                        `confirmCode` = NULL,
                        `lastLogin` = NOW(),
                        `modified` = `modified`
                    WHERE
                        `customerId` = ' . db_int($iCustomerId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * set Customer in session
     *
     * @param Customer $oCustomer
     */
    public static function setCustomerInSession(Customer $oCustomer)
    {
        $oCustomer->maskPass(); // mask pass XXX for session
        CustomerCSRFSynchronizerToken::get(true); // force a new CSRF token
        Session::set(static::SESSION, $oCustomer); // set Customer in session
    }

    /**
     * get Customer by debnumber and pass and set in session
     *
     * @param string $sDebNr
     * @param string $sPassword
     *
     * @return mixed Customer/false
     */
    public static function login($sDebNr, $sPassword)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `customers`
                    WHERE
                        `debnr` = ' . db_str($sDebNr) . '
                    AND
                        `password` = ' . db_str(hashPasswordForDb($sPassword)) . '
                    AND
                        `online` = 1
                    AND
                        `deleted` = 0    
                    AND
                        (`locked` IS NULL OR `locked` <= ' . db_date(
                Date::strToDate('now')
                    ->addMinutes(-1 * AccessLogManager::account_locked_time)
                    ->format(Date::FORMAT_DB_F)
            ) . ')
                    LIMIT 1
                    ;';

        $oDb       = DBConnections::get();
        $oCustomer = $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Customer");

        if (!empty($oCustomer)) {
            self::updateLastLoginByCustomerId($oCustomer->customerId); //update last login date and time
            self::setCustomerInSession($oCustomer); // set Customer in session

            return $oCustomer;
        }

        # no Customer found return false
        return false;
    }

    /**
     * update a Customer's password and then login
     *
     * @param Customer $oCustomer
     *
     * @return mixed Customer/false
     */
    public static function updatePasswordAndLogin(Customer $oCustomer)
    {
        # update the Customer's password
        $sQuery = ' UPDATE
                        `customers`
                    SET
                        `password` = ' . db_str($oCustomer->password) . ',
                        `confirmCode` = NULL,
                        `lastLogin` = NOW()
                    WHERE
                        `customerId` = ' . db_int($oCustomer->customerId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        # login
        self::setCustomerInSession($oCustomer); // set Customer in session
    }

    /**
     * logout Customer
     *
     * @param string $sRedirectLocation (redirect location)
     */
    public static function logout($sRedirectLocation)
    {
        unset($_SESSION['oCurrentCustomer']);
        CustomerCSRFSynchronizerToken::get(true); // force a new CSRF token
        http_redirect($sRedirectLocation); //go to redirect location
    }

    /**
     * lock customer by customername
     *
     * @param string $sEmail
     * @param string $sReason
     */
    public static function lockCustomerByEmail($sDebNr, $sReason)
    {
        $sQuery = ' UPDATE
                        `customers`
                    SET
                        `locked` = NOW(),
                        `lockedReason` = ' . db_str($sReason) . '
                    WHERE
                        `debnr` = ' . db_str($sDebNr) . '
                    AND
                        (`locked` IS NULL OR `locked` <= ' . db_date(
                Date::strToDate('now')
                    ->addMinutes(-1 * AccessLogManager::account_locked_time)
                    ->format(Date::FORMAT_DB_F)
            ) . ')
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * unlock customer
     *
     * @param Customer $oCustomer
     * @param string   $sReason
     */
    public static function unlockCustomer(Customer $oCustomer, $sReason)
    {
        $sQuery = ' UPDATE
                        `customers`
                    SET
                        `locked` = NULL,
                        `lockedReason` = ' . db_str($sReason) . '
                    WHERE
                        `customerId` = ' . db_int($oCustomer->customerId) . '
                    AND
                        `locked` IS NOT NULL
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

}
