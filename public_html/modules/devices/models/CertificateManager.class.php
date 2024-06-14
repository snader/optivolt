<?php

class CertificateManager
{

    /**
     * get the full Certificate object by id
     *
     * @param int $iCertificateId
     * @param int $iLocaleId
     *
     * @return Certificate
     */
    public static function getCertificateById($iCertificateId)
    {
        $sQuery = ' SELECT
                        `c`.*
                    FROM
                        `certificates` AS `c`
                    WHERE
                        `c`.`certificateId` = ' . db_int($iCertificateId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Certificate");
    }

    /**
     * save Certificate object
     *
     * @param Certificate $oCertificate
     */
    public static function saveCertificate(Certificate $oCertificate)
    {
        # save item
        $sQuery = ' INSERT INTO `certificates` (
                        `certificateId`,                                               
                        `deviceId`,
                        `userId`,
                        `vbbNr`,
                        `testInstrument`,
                        `testSerialNr`,
                        `nextcheck`,                        
                        `visualCheck`,    
                        `weerstandBeLeRPE`,    
                        `isolatieWeRISO`,    
                        `lekstroomIEA`,    
                        `lekstroomTouch`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oCertificate->certificateId) . ',
                        ' . db_int($oCertificate->deviceId) . ',
                        ' . db_int($oCertificate->userId) . ',
                        ' . db_str($oCertificate->vbbNr) . ',                        
                        ' . db_str($oCertificate->testInstrument) . ',
                        ' . db_str($oCertificate->testSerialNr) . ',
                        ' . db_str($oCertificate->nextcheck) . ',
                        ' . db_str($oCertificate->visualCheck) . ',                        
                        ' . db_str($oCertificate->weerstandBeLeRPE) . ', 
                        ' . db_str($oCertificate->isolatieWeRISO) . ', 
                        ' . db_str($oCertificate->lekstroomIEA) . ', 
                        ' . db_str($oCertificate->lekstroomTouch) . ', 
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE                                                
                        `deviceId`=VALUES(`deviceId`),
                        `userId`=VALUES(`userId`),
                        `vbbNr`=VALUES(`vbbNr`),
                        `testInstrument`=VALUES(`testInstrument`),
                        `testSerialNr`=VALUES(`testSerialNr`),
                        `nextcheck`=VALUES(`nextcheck`),
                        `visualCheck`=VALUES(`visualCheck`),
                        `weerstandBeLeRPE`=VALUES(`weerstandBeLeRPE`),
                        `isolatieWeRISO`=VALUES(`isolatieWeRISO`),
                        `lekstroomIEA`=VALUES(`lekstroomIEA`),
                        `lekstroomTouch`=VALUES(`lekstroomTouch`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oCertificate->certificateId === null) {
            $oCertificate->certificateId = $oDb->insert_id;
        }

        
    }

    /**
     * delete item and all media
     *
     * @param Certificate $oCertificate
     *
     * @return Boolean
     */
    public static function deleteCertificate(Certificate $oCertificate)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oCertificate->isDeletable()) {

            $sQuery = "DELETE FROM `certificates` WHERE `certificateId` = " . db_int($oCertificate->certificateId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }


    
    /**
     * get the full Certificate object by iDeviceId
     *
     * @param int $iDeviceId     
     *
     * @return Certificates
     */
    public static function getCertificatesByDeviceId($iDeviceId)
    {
        $sQuery = ' SELECT
                        `c`.*,
                        `u`.`name`,
                        `u`.`userId`
                    FROM
                        `certificates` AS `c`
                    LEFT JOIN `users` AS `u` ON `u`.`userId` = `c`.`userId`      
                    WHERE
                        `c`.`deviceId` = ' . db_int($iDeviceId) . '
                    ORDER BY `c`.`certificateId` DESC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "Certificate");
    }
    

    /**
     * return Certificate items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array Certificate
     */
    public static function getCertificatesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`c`.`created`' => 'DESC', '`c`.`modified`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

       
        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`c`.`testSerialNr` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `c`.`testInstrument` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `c`.`vbbNr` LIKE ' . db_str(
                    '%' . $aFilter['q'] . '%'
                ) . ')';
        }

        # get by userId
        if (isset($aFilter['userId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`c`.`userId` = ' . db_int($aFilter['userId']);
        }

        # get by deviceId
        if (isset($aFilter['deviceId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`c`.`deviceId` = ' . db_int($aFilter['deviceId']);
        }

        # get items with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`c`.`modified`, `c`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
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
                        `c`.*,
                        `d`.*
                    FROM
                        `certificates` AS `c` 
                    LEFT JOIN `devices` AS `d` ON `d`.`deviceId` = `c`.`deviceId`    
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aCertificates = $oDb->query($sQuery, QRY_OBJECT, "Certificate");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aCertificates;
    }

    

}