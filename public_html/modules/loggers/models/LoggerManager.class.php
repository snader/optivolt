<?php

class LoggerManager
{

    /**
     * get the full Logger object by id
     *
     * @param int $iLoggerId
     *
     * @return Logger
     */
    public static function getLoggerById($iLoggerId)
    {
        $sQuery = ' SELECT
                        `l`.*
                    FROM
                        `loggers` AS `l`
                    WHERE
                        `l`.`loggerId` = ' . db_int($iLoggerId) . ' AND `l`.`deleted` = ' . db_int(0) . '
                    ;';


        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Logger");
    }


    /**
     * get the full Logger object by sName
     *
     * @param int $sName
     *
     * @return Logger
     */
    public static function getLoggerByName($sName)
    {
        $sQuery = ' SELECT
                        `l`.*
                    FROM
                        `loggers` AS `l`
                    WHERE
                        `l`.`name` = ' . db_str($sName) . ' AND `l`.`deleted` = ' . db_int(0) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Logger");
    }

    /**
     * save Logger object
     *
     * @param Logger $oLogger
     */
    public static function saveLogger(Logger $oLogger)
    {
        # save item
        $sQuery = ' INSERT INTO `loggers` (
                        `loggerId`,
                        `name`,
                        `online`,
                        `order`,
                        `availableFrom`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oLogger->loggerId) . ',
                        ' . db_str($oLogger->name) . ',
                        ' . db_int($oLogger->online) . ',
                        ' . db_int($oLogger->order) . ',
                        ' . db_date($oLogger->availableFrom) . ',
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE

                        `name`=VALUES(`name`),
                        `order`=VALUES(`order`),
                        `online`=VALUES(`online`),
                        `availableFrom`=VALUES(`availableFrom`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oLogger->loggerId === null) {
            $oLogger->loggerId = $oDb->insert_id;
        }


    }

    /**
     * delete item and all media
     *
     * @param Logger $oLogger
     *
     * @return Boolean
     */
    public static function deleteLogger(Logger $oLogger)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oLogger->isDeletable()) {

            //$sQuery = "DELETE FROM `loggers` WHERE `loggerId` = " . db_int($oLogger->loggerId) . ";";
            $sQuery = "UPDATE `loggers` SET `deleted` = 1, `name`=" . db_str($oLogger->name . '-' . time()) . " WHERE `loggerId` = " . db_int($oLogger->loggerId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online by Logger item id
     *
     * @param int $bOnline
     * @param int $iLoggerId
     *
     * @return boolean
     */
    public static function updateOnlineByLoggerId($bOnline, $iLoggerId)
    {
        $sQuery =
        ' UPDATE
                        `loggers`
                    SET
                        `online` = ' . db_int($bOnline) .
        '
                    WHERE
                        `loggerId` = ' . db_int($iLoggerId) .
        ' AND `l`.`deleted` = ' . db_int(0) . '
                    ;
        ';
        $oDb    = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }



    /**
     * return Logger items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array Logger
     */
    public static function getLoggersByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`l`.`order`' => 'ASC', '`l`.`name`' => 'ASC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';


        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `l`.`deleted` = 0
                        ';

        // no show all? only show online items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `l`.`online` = 1
                        ';

        }

        $sFrom    .=  ' LEFT JOIN `planning` AS `p` ON `p`.`loggerId` = `l`.`loggerId`' . PHP_EOL;
        if (isset($aFilter['startDate'])) {
            $aFilter['startDate'] = date('Y-m-d', strtotime(date("Y-m-d", strtotime($aFilter['startDate'])) . "-10 day")); // OFFSET
            $sFrom .= ($sFrom != '' ? ' AND ' : '') . '(`p`.`startDate` >= ' . db_str($aFilter['startDate']) . ')';
        }
        if (isset($aFilter['endDate'])) {
            $aFilter['endDate'] = date('Y-m-d', strtotime(date("Y-m-d", strtotime($aFilter['endDate'])) . "+10 day")); // OFFSET
            $sFrom .= ($sFrom != '' ? ' AND ' : '') . '(`p`.`endDate` <= ' . db_str($aFilter['endDate']) . ')';
        }


        $aOrderBy['`p`.`startDate`'] = 'ASC';
        $sFrom    .= ' LEFT JOIN `customers` AS `c` ON `c`.`customerId` = `p`.`customerId`' . PHP_EOL;
        if (!empty($aFilter['customerId']) && is_numeric($aFilter['customerId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`c`.`customerId` = ' . db_int($aFilter['customerId']) . ')';
        }

        # get only loggerId
        if (!empty($aFilter['loggerId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`l`.`loggerId` = ' . db_int($aFilter['loggerId']) . ')';
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`l`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ')';
        }

        # get items with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`l`.`modified`, `l`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
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
                        `l`.*,
                        `l`.`loggerId` AS `mainLoggerId`,
                        `c`.`companyName`,
                        `p`.*
                    FROM
                        `loggers` AS `l`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aLoggers = $oDb->query($sQuery, QRY_OBJECT, "Logger");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aLoggers;
    }


    /**
     * return Logger items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array Logger
     */
    public static function getLoggersOnlyByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`l`.`LoggerId`' => 'ASC', '`l`.`name`' => 'ASC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
        `l`.`deleted` = 0
    ';

        // no show all? only show online items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `l`.`online` = 1
                        ';
        }


        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`l`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ')';
        }

        if (!empty($aFilter['min']) && !empty($aFilter['max'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`l`.`loggerId` >= ' . db_int($aFilter['min']) . ' AND `l`.`loggerId` <= ' . db_int($aFilter['max']) . ')';
        }

        # get items with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`l`.`modified`, `l`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
        }

        # handle order by
        $sOrderBy = '';
        if (count($aOrderBy) > 0) {
            foreach ($aOrderBy as $sColumn => $sOrder) {
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
                        `l`.*
                    FROM
                        `loggers` AS `l`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        //_d($sQuery);

        $oDb        = DBConnections::get();
        $aLoggers = $oDb->query($sQuery, QRY_OBJECT, "Logger");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aLoggers;
    }


    ///////////////////
    /**
     * return Logger items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array Logger
     */
    public static function getLoggersByFilterForOutput(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`l`.`order`' => 'ASC', '`l`.`name`' => 'ASC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
        `l`.`deleted` = 0
    ';

        // no show all? only show online items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `l`.`online` = 1
                        ';
        }

        $sFrom    .=  ' LEFT JOIN `planning` AS `p` ON `p`.`loggerId` = `l`.`loggerId`' . PHP_EOL;
        /* if (isset($aFilter['startDate'])) {
            $aFilter['startDate'] = date('Y-m-d', strtotime(date("Y-m-d", strtotime($aFilter['startDate'])) . "-10 day")); // OFFSET
            $sFrom .= ($sFrom != '' ? ' AND ' : '') . '(`p`.`startDate` >= ' . db_str($aFilter['startDate']) . ')';
        }
        if (isset($aFilter['endDate'])) {
            $sFrom .= ($sFrom != '' ? ' AND ' : '') . '(`p`.`endDate` <= ' . db_str($aFilter['endDate']) . ')';
        }*/
        if (isset($aFilter['startDate']) && isset($aFilter['endDate'])) {

            if (isset($aFilter['startDate'])) {
                //$aFilter['startDate'] = date('Y-m-d', strtotime(date("Y-m-d", strtotime($aFilter['startDate'])) . "-10 day")); // OFFSET

            }

            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(

        ( (' . db_str($aFilter['startDate']) . ' >= `p` . `startDate`) AND (' . db_str($aFilter['startDate']) . ' <= `p`.`endDate`) ) OR
        ( (' . db_str($aFilter['endDate']) . ' >= `p` . `startDate`) AND (' . db_str($aFilter['endDate']) . ' <= `p`.`endDate`) ) OR
        ( (' . db_str($aFilter['startDate']) . ' <= `p` . `startDate`) AND (' . db_str($aFilter['endDate']) . ' >= `p`.`endDate`) )

        )';
        }


        $aOrderBy['`p`.`startDate`'] = 'ASC';
        $sFrom    .= ' LEFT JOIN `customers` AS `c` ON `c`.`customerId` = `p`.`customerId`' . PHP_EOL;
        if (!empty($aFilter['customerId']) && is_numeric($aFilter['customerId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`c`.`customerId` = ' . db_int($aFilter['customerId']) . ')';
        }

        # get only loggerId
        if (!empty($aFilter['loggerId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`l`.`loggerId` = ' . db_int($aFilter['loggerId']) . ')';
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`l`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ')';
        }

        # get items with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`l`.`modified`, `l`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
        }

        # handle order by
        $sOrderBy = '';
        if (count($aOrderBy) > 0) {
            foreach ($aOrderBy as $sColumn => $sOrder) {
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
                        `l`.*,
                        `l`.`loggerId` AS `mainLoggerId`,
                        `c`.`companyName`,
                        `p`.*
                    FROM
                        `loggers` AS `l`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aLoggers = $oDb->query($sQuery, QRY_OBJECT, "Logger");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aLoggers;
    }
    /////////////





}