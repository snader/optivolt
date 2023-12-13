<?php

class LogManager
{

    /**
     * get the full Log object by id
     *
     * @param int $iLogId
     * @param int $iLocaleId
     *
     * @return Log
     */
    public static function getLogById($iLogId)
    {
        $sQuery = ' SELECT
                        `l`.*
                    FROM
                        `logs` AS `l`
                    WHERE
                        `l`.`logId` = ' . db_int($iLogId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Log");
    }

    /**
     * save Log object
     *
     * @param Log $oLog
     */
    public static function saveLog(Log $oLog)
    {
        # save item
        $sQuery = ' INSERT INTO `logs` (
                        `logId`,
                        `userId`,
                        
                        `title`,
                        `name`,
                        `link`,
                        `content`,
                        `online`,
                        
                        `created`
                    )
                    VALUES (
                        ' . db_int($oLog->logId) . ',
                        ' . db_int($oLog->userId) . ',
                        
                        ' . db_str($oLog->title) . ',
                        ' . db_str($oLog->name) . ',
                        ' . db_str($oLog->link) . ',
                        ' . db_str(strip_tags($oLog->content)) . ',
                        ' . db_int($oLog->online) . ',
                
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `userId`=VALUES(`userId`),
                        
                        `title`=VALUES(`title`),
                        `name`=VALUES(`name`),
                        `link`=VALUES(`link`),
                        `content`=VALUES(`content`),
             
                        `online`=VALUES(`online`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oLog->logId === null) {
            $oLog->logId = $oDb->insert_id;
        }

        
    }

    /**
     * delete item and all media
     *
     * @param Log $oLog
     *
     * @return Boolean
     */
    public static function deleteLog(Log $oLog)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oLog->isDeletable()) {
          
            $sQuery = "DELETE FROM `logs` WHERE `logId` = " . db_int($oLog->logId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online by Log item id
     *
     * @param int $bOnline
     * @param int $iLogId
     *
     * @return boolean
     */
    public static function updateOnlineByLogId($bOnline, $iLogId)
    {
        $sQuery = ' UPDATE
                        `logs`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `logId` = ' . db_int($iLogId) . '
                    ;';
        $oDb    = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * return Log items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array Log
     */
    public static function getLogsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`l`.`logId`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        

        // no show all? only show online items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `l`.`online` = 1
                        ';
            
        }

     
        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`l`.`title` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `l`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `l`.`link` LIKE ' . db_str(
                    '%' . $aFilter['q'] . '%'
                ) . ')';
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
                        `l`.*
                    FROM
                        `logs` AS `l`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aLogs = $oDb->query($sQuery, QRY_OBJECT, "Log");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aLogs;
    }

    

}