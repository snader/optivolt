<?php

class DeviceManager
{

    /**
     * get the full Device object by id
     *
     * @param int $iDeviceId
     * @param int $iLocaleId
     *
     * @return Device
     */
    public static function getDeviceById($iDeviceId)
    {
        $sQuery = ' SELECT
                        `d`.*
                    FROM
                        `devices` AS `d`
                    WHERE
                        `d`.`deviceId` = ' . db_int($iDeviceId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Device");
    }

    /**
     * save Device object
     *
     * @param Device $oDevice
     */
    public static function saveDevice(Device $oDevice)
    {
        # save item
        $sQuery = ' INSERT INTO `devices` (
                        `deviceId`,                                               
                        `name`,
                        `brand`,
                        `type`,
                        `serial`,
                        `online`,                        
                        `created`
                    )
                    VALUES (
                        ' . db_int($oDevice->deviceId) . ',
                        ' . db_str($oDevice->name) . ',                        
                        ' . db_str($oDevice->brand) . ',
                        ' . db_str($oDevice->type) . ',
                        ' . db_str($oDevice->serial) . ',
                        ' . db_int($oDevice->online) . ',                        
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE                                                
                        `name`=VALUES(`name`),
                        `brand`=VALUES(`brand`),
                        `type`=VALUES(`type`),
                        `serial`=VALUES(`serial`),
                        `online`=VALUES(`online`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oDevice->deviceId === null) {
            $oDevice->deviceId = $oDb->insert_id;
        }

        
    }

    /**
     * delete item and all media
     *
     * @param Device $oDevice
     *
     * @return Boolean
     */
    public static function deleteDevice(Device $oDevice)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oDevice->isDeletable()) {

            $sQuery = "DELETE FROM `devices` WHERE `deviceId` = " . db_int($oDevice->deviceId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online by Device item id
     *
     * @param int $bOnline
     * @param int $iDeviceId
     *
     * @return boolean
     */
    public static function updateOnlineByDeviceId($bOnline, $iDeviceId)
    {
        $sQuery = ' UPDATE
                        `devices`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `deviceId` = ' . db_int($iDeviceId) . '
                    ;';
        $oDb    = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * return Device items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array Device
     */
    public static function getDevicesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`d`.`name`' => 'ASC', '`d`.`deviceId`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        

        // no show all? only show online items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `d`.`online` = 1
                        ';
            
        }


        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`d`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `d`.`type` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `d`.`brand` LIKE ' . db_str(
                    '%' . $aFilter['q'] . '%'
                ) . ')';
        }

        # get items with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`d`.`modified`, `d`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
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

        $sGroupBy = '`d`.`deviceId` ';

        $sQuery = ' SELECT ' . ($iFoundRows !== false ? 'SQL_CALC_FOUND_ROWS' : '') . '
                        `d`.*,
                        `c`.*
                    FROM
                        `devices` AS `d`                    
                    ' . $sFrom . '
                    LEFT JOIN `certificates` AS `c` ON `c`.`deviceId` = `d`.`deviceId`  
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aDevices = $oDb->query($sQuery, QRY_OBJECT, "Device");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aDevices;
    }

    

}