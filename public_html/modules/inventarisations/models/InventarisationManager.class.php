<?php

class InventarisationManager
{

    /**
     * get the full Inventarisation object by id
     *
     * @param int $iInventarisationId
     * @param int $iLocaleId
     *
     * @return Inventarisation
     */
    public static function getInventarisationById($iInventarisationId)
    {
        $sQuery = ' SELECT
                        `i`.*
                    FROM
                        `inventarisations` AS `i`
                    WHERE
                        `i`.`inventarisationId` = ' . db_int($iInventarisationId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Inventarisation");
    }

    /**
     * save Inventarisation object
     *
     * @param Inventarisation $oInventarisation
     */
    public static function saveInventarisation(Inventarisation $oInventarisation)
    {
        # save item
        $sQuery = ' INSERT INTO `inventarisations` (
                        `inventarisationId`,
                        `parentInventarisationId`,
                        `loggerId`,
                        `userId`,
                        `customerId`,    
                        `customerName`,                    
                        `name`,
                        `kva`,
                        `position`,
                        `freeFieldAmp`,
                        `stroomTrafo`,
                        `control`,
                        `type`,
                        `relaisNr`,
                        `engineKw`,
                        `turningHours`,
                        `photoNrs`,
                        `trafoNr`,
                        `mlProposed`,
                        `remarks`,                        
                        `created`
                    )
                    VALUES (
                        ' . db_int($oInventarisation->inventarisationId) . ',
                        ' . db_int($oInventarisation->parentInventarisationId) . ',
                        ' . db_int($oInventarisation->loggerId) . ',
                        ' . db_int($oInventarisation->userId) . ',
                        ' . db_int($oInventarisation->customerId) . ', 
                        ' . db_str($oInventarisation->customerName) . ',                       
                        ' . db_str($oInventarisation->name) . ',
                        ' . db_int($oInventarisation->kva) . ',
                        ' . db_str($oInventarisation->position) . ',
                        ' . db_str($oInventarisation->freeFieldAmp) . ',
                        ' . db_str($oInventarisation->stroomTrafo) . ',
                        ' . db_str($oInventarisation->control) . ',
                        ' . db_str($oInventarisation->type) . ',
                        ' . db_str($oInventarisation->relaisNr) . ',
                        ' . db_str($oInventarisation->engineKw) . ',
                        ' . db_str($oInventarisation->turningHours) . ',
                        ' . db_str($oInventarisation->photoNrs) . ',
                        ' . db_str($oInventarisation->trafoNr) . ',
                        ' . db_str($oInventarisation->mlProposed) . ',
                        ' . db_str($oInventarisation->remarks) . ',
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `parentInventarisationId`= VALUES(`parentInventarisationId`),
                        `loggerId`=VALUES(`loggerId`),
                        `userId`=VALUES(`userId`),
                        `customerId`=VALUES(`customerId`),
                        `customerName`=VALUES(`customerName`),
                        `name`=VALUES(`name`),
                        `kva`=VALUES(`kva`),
                        `position`=VALUES(`position`),
                        `freeFieldAmp`=VALUES(`freeFieldAmp`),
                        `stroomTrafo`=VALUES(`stroomTrafo`),
                        `control`=VALUES(`control`),
                        `type`=VALUES(`type`),
                        `relaisNr`=VALUES(`relaisNr`),
                        `engineKw`=VALUES(`engineKw`),
                        `turningHours`=VALUES(`turningHours`),                        
                        `photoNrs`=VALUES(`photoNrs`),
                        `trafoNr`=VALUES(`trafoNr`),
                        `mlProposed`=VALUES(`mlProposed`),
                        `remarks`=VALUES(`remarks`)
                    ;';

                    //if (is_int($oInventarisation->parentInventarisationId)) {
                    //_d($sQuery);
                    //}

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oInventarisation->inventarisationId === null) {
            $oInventarisation->inventarisationId = $oDb->insert_id;
        }

        
    }

    /**
     * 
     */
    public static function getInventarisationTreeById($iInventarisationId)
    {

        $sQuery = ' SELECT
                        `i`.*
                    FROM
                        `inventarisations` AS `i`
                    WHERE
                        `i`.`InventarisationId` = ' . db_int($iInventarisationId) . ' OR 
                        `i`.`parentInventarisationId` = ' . db_int($iInventarisationId) . ' 
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "Inventarisation");
    }

    /**
     *
     */
    public static function getSubInventarisations($iInventarisationId)
    {
        $sQuery = ' SELECT
                        `i`.*
                    FROM
                        `inventarisations` AS `i`
                    WHERE
                        `i`.`parentInventarisationId` = ' . db_int($iInventarisationId) . ' 
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "Inventarisation");
    }


    /**
     * delete item and all media
     *
     * @param Inventarisation $oInventarisation
     *
     * @return Boolean
     */
    public static function deleteInventarisation(Inventarisation $oInventarisation)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oInventarisation->isDeletable()) {

            

            $sQuery = "DELETE FROM `inventarisations` WHERE `inventarisationId` = " . db_int($oInventarisation->inventarisationId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online by Inventarisation item id
     *
     * @param int $bOnline
     * @param int $iInventarisationId
     *
     * @return boolean
     */
    public static function updateOnlineByInventarisationId($bOnline, $iInventarisationId)
    {
        $sQuery = ' UPDATE
                        `inventarisations`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `inventarisationId` = ' . db_int($iInventarisationId) . '
                    ;';
        $oDb    = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * return Inventarisation items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array Inventarisation
     */
    public static function getInventarisationsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`i`.`created`' => 'ASC', '`i`.`inventarisationId`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        

        // no show all? only show online items
        if (isset($aFilter['isParent'])) {
            
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`i`.`parentInventarisationId` IS NULL';
        }

        # get by customerId
        if (isset($aFilter['customerId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`i`.`customerId` = ' . db_int($aFilter['customerId']);
        }

        # get by userId
        if (isset($aFilter['userId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`i`.`userId` = ' . db_int($aFilter['userId']);
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`i`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `i`.`customerName` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `i`.`remarks` LIKE ' . db_str(
                    '%' . $aFilter['q'] . '%'
                ) . ')';
        }

        # get items with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`i`.`modified`, `i`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
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
                        `i`.*
                    FROM
                        `inventarisations` AS `i`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aInventarisations = $oDb->query($sQuery, QRY_OBJECT, "Inventarisation");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aInventarisations;
    }

    

}