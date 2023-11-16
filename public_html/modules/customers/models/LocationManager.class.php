<?php

class LocationManager
{

    /**
     * get the full Location object by id
     *
     * @param int $iLocationId
     * @param int $iLocaleId
     *
     * @return Location
     */
    public static function getLocationById($iLocationId)
    {
        $sQuery = ' SELECT
                        `s`.*
                    FROM
                        `locations` AS `s`
                    WHERE
                        `s`.`locationId` = ' . db_int($iLocationId) . ' AND `s`.`deleted` = ' . db_int(0) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Location");
    }

    /**
     *
     */
    public static function getLocationByName($sName, $iCustomerId)
    {
        $sQuery = ' SELECT
                        `s`.*
                    FROM
                        `locations` AS `s`
                    WHERE
                        `s`.`name` = ' . db_str($sName) . ' AND `s`.`deleted` = ' . db_int(0) . ' AND `s`.`customerId` = ' . db_int($iCustomerId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Location");
    }


    /**
     * save Location object
     *
     * @param Location $oLocation
     */
    public static function saveLocation(Location $oLocation)
    {
        # save item
        $sQuery = ' INSERT INTO `locations` (
                        `locationId`,
                        `customerId`,
                        `name`,
                        `notice`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oLocation->locationId) . ',
                        ' . db_int($oLocation->customerId) . ',
                        ' . db_str($oLocation->name) . ',
                        ' . db_str($oLocation->notice) . ',
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `customerId`=VALUES(`customerId`),
                        `name`=VALUES(`name`),
                        `notice`=VALUES(`notice`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oLocation->locationId === null) {
            $oLocation->locationId = $oDb->insert_id;
        }

    }

    /**
     * delete item and all media
     *
     * @param Location $oLocation
     *
     * @return Boolean
     */
    public static function deleteLocation(Location $oLocation)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oLocation->isDeletable()) {

            $aSystems = $oLocation->getSystems();
            foreach ($aSystems as $oSystem) {

                $aSystemReports = SystemReportManager::getSystemReportsByFilter(['systemId' => $oSystem->systemId]);
                foreach ($aSystemReports as $oSystemReport) {
                        SystemReportManager::deleteSystemReport($oSystemReport);
                }
                SystemManager::deleteSystem($oSystem);
            }

            $sQuery = "UPDATE `systems` SET `deleted`=1 WHERE `locationId` = " . db_int($oLocation->locationId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);


            $sQuery = "UPDATE `locations` SET `deleted`=1 WHERE `locationId` = " . db_int($oLocation->locationId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online by Location item id
     *
     * @param int $bOnline
     * @param int $iLocationId
     *
     * @return boolean
     */
    public static function updateOnlineByLocationId($bOnline, $iLocationId)
    {
        $sQuery = ' UPDATE
                        `locations`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `locationId` = ' . db_int($iLocationId) . '
                    ;';
        $oDb    = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * return Location items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array Location
     */
    public static function getLocationsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`s`.`name`' => 'ASC', '`s`.`locationId`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
        `s`.`deleted` = 0
    ';

        # get by customerId
        if (isset($aFilter['customerId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`customerId` = ' . db_int($aFilter['customerId']);
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`s`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `s`.`notice` LIKE ' . db_str(
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
                        `s`.*
                    FROM
                        `locations` AS `s`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aLocations = $oDb->query($sQuery, QRY_OBJECT, "Location");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aLocations;
    }




}