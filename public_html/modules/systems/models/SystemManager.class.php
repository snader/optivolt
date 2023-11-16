<?php

class SystemManager
{

    /**
     * get the full System object by id
     *
     * @param int $iSystemId
     *
     * @return System
     */
    public static function getSystemById($iSystemId)
    {
        $sQuery = ' SELECT
                        `s`.*
                    FROM
                        `systems` AS `s`
                    WHERE
                        `s`.`systemId` = ' . db_int($iSystemId) . ' AND `s`.`deleted` = ' . db_int(0) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "System");
    }

    public static function getSystemByNamePosLocationId($iLocationId, $sPos, $sName)
    {
        $sQuery = ' SELECT
                        `s`.*
                    FROM
                        `systems` AS `s`
                    WHERE
                        `s`.`locationId` = ' . db_int($iLocationId) . ' AND
                        `s`.`pos` = ' . db_str($sPos) . ' AND `s`.`deleted` = ' . db_int(0) . '
                    LIMIT 0,1
                    ;';
                    /*
                    AND
                        `s`.`name` = ' . db_str($sName) . '
                    */

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "System");
    }

    /**
     *
     */
    public static function updateLastReportDate($iSystemId, $sDate = null)
    {

        $sQuery = 'UPDATE `systems`
                    SET `lastReportDate` = ' . (!empty($sDate) ? db_str($sDate) : 'NOW()') . '
                    WHERE `systemId` = ' . db_int($iSystemId) . ';';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

    }

    /**
     * save System object
     *
     * @param System $oSystem
     */
    public static function saveSystem(System $oSystem)
    {
        # save item
        $sQuery = ' INSERT INTO `systems` (
                        `systemId`,
                        `systemTypeId`,
                        `locationId`,
                        `floor`,
                        `pos`,
                        `name`,
                        `model`,
                        `columnA`,
                        `machineNr`,
                        `installDate`,
                        `online`,
                        `notice`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oSystem->systemId) . ',
            ' . db_int($oSystem->systemTypeId) . ',
                        ' . db_int($oSystem->locationId) . ',
                        ' . db_str($oSystem->floor) . ',
                        ' . db_str($oSystem->pos) . ',
                        ' . db_str($oSystem->name) . ',
                        ' . db_str($oSystem->model) . ',
                        ' . db_str($oSystem->columnA) . ',
                        ' . db_str($oSystem->machineNr) . ',
                        ' . db_date($oSystem->installDate) . ',
                        ' . db_int($oSystem->online) . ',
                        ' . db_str($oSystem->notice) . ',
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `systemTypeId`=VALUES(`systemTypeId`),
                        `locationId`=VALUES(`locationId`),
                        `floor`=VALUES(`floor`),
                        `pos`=VALUES(`pos`),
                        `name`=VALUES(`name`),
                        `model`=VALUES(`model`),
                        `columnA`=VALUES(`columnA`),
                        `machineNr`=VALUES(`machineNr`),
                        `installDate`=VALUES(`installDate`),
                        `notice`=VALUES(`notice`),
                        `online`=VALUES(`online`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oSystem->systemId === null) {
            $oSystem->systemId = $oDb->insert_id;
        }

    }

    /**
     * delete item and all media
     *
     * @param System $oSystem
     *
     * @return Boolean
     */
    public static function deleteSystem(System $oSystem)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oSystem->isDeletable()) {

            $aFilter['systemId'] = $oSystem->systemId;
            $aSystemReports = SystemReportManager::getSystemReportsByFilter($aFilter);
            foreach ($aSystemReports as $oSystemReport) {
                SystemReportManager::deleteSystemReport($oSystemReport);
            }

            $sQuery = "UPDATE `systems` SET `deleted` = 1 WHERE `systemId` = " . db_int($oSystem->systemId) . ";";
            //$sQuery = "DELETE FROM `systems` WHERE `systemId` = " . db_int($oSystem->systemId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online by System item id
     *
     * @param int $bOnline
     * @param int $iSystemId
     *
     * @return boolean
     */
    public static function updateOnlineBySystemId($bOnline, $iSystemId)
    {
        $sQuery = ' UPDATE
                        `systems`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `systemId` = ' . db_int($iSystemId) . ' AND `deleted` = ' . db_int(0) . '
                    ;';
        $oDb    = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     *
     */
    public static function countSystemsByLocation($iLocationId, $year = null)
    {


        $sQuery = "SELECT count(`locationId`) as 'amount' FROM `systems` WHERE `online`=1 AND `deleted`=0 AND `locationId` = " . db_int($iLocationId) . ($year ? ' AND `lastReportDate` LIKE ' . db_str($year . '%') : '');

        $oDb                = DBConnections::get();
        $aResult            = $oDb->query($sQuery, QRY_UNIQUE_ARRAY);
        $iAmountOfSystems = $aResult['amount'];

        return $iAmountOfSystems;
    }


    /**
     * return System items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array System cast(column as unsigned)
     */
    public static function getSystemsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`l`.`name`' => 'ASC', 'cast(`s`.`pos` as unsigned)' => 'ASC', '`s`.`pos`' => 'ASC', '`s`.`name`' => 'ASC', '`s`.`systemId`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
        `s`.`deleted` = 0
    ';

        // no show all? only show online items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `s`.`online` = 1
                        ';
        }

        if (!empty($aFilter['orderBy'])) {
            $aOrderBy = $aFilter['orderBy'];
        }

        $sFrom  .= ' JOIN `locations` AS `l` ON `l`.`locationId` = `s`.`locationId`';
        $sFrom  .= ' JOIN `customers` AS `c` ON `c`.`customerId` = `l`.`customerId`';
        $sFrom  .= ' LEFT JOIN `system_types` AS `st` ON `s`.`systemTypeId` = `st`.`systemTypeId`';

        # get by customerId
        if (!empty($aFilter['customerId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`l`.`customerId` = ' . db_int($aFilter['customerId']);
        }
        # get by locationId
        if (isset($aFilter['locationId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`locationId` = ' . db_int($aFilter['locationId']);
        }

        # based on logged in user and his planning
        if (!empty($aFilter['userId'])) {

            $sFrom  .= ' JOIN `users_customers` AS `uc` ON `uc`.`customerId` = `c`.`customerId`';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`uc`.`userId` = ' . db_int($aFilter['userId']);

            if (!empty($aFilter['visitDate'])) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`uc`.`visitDate` = ' . db_str($aFilter['visitDate']);
            }

            if (isset($aFilter['finished'])) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`uc`.`finished` = ' . db_int($aFilter['finished']);
            }

            $sGroupBy = ' `s`.`systemId`';

        }


        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`s`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `s`.`model` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `s`.`machineNr` LIKE ' . db_str(
                    '%' . $aFilter['q'] . '%'
                ) . ')';
        }

        # get items with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`s`.`modified`, `s`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
        }

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
                        `s`.*,
                        `l`.`name` as \'locationName\',
                        `c`.`companyName`,
                        `st`.`typeName`
                        ' . (!empty($aFilter['userId']) ? ', `uc`.*' : '')  . '

                    FROM
                        `systems` AS `s`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';


        $oDb        = DBConnections::get();
        $aSystems = $oDb->query($sQuery, QRY_OBJECT, "System");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aSystems;
    }




}