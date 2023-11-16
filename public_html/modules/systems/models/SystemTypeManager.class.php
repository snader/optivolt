<?php

class SystemTypeManager
{

    /**
     * get the full System object by id
     *
     * @param int $iSystemId
     *
     * @return System
     */
    public static function getSystemTypeById($iSystemTypeId)
    {
        $sQuery = ' SELECT
                        `s`.*
                    FROM
                        `system_types` AS `s`
                    WHERE
                        `s`.`systemTypeId` = ' . db_int($iSystemTypeId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "SystemType");
    }


    /**
     * save SystemType object
     *
     * @param SystemType $oSystemType
     */
    public static function saveSystemType(SystemType $oSystemType)
    {
        # save item
        $sQuery = ' INSERT INTO `system_types` (
                        `systemTypeId`,
                        `typeName`
                    )
                    VALUES (
                        ' . db_int($oSystemType->systemTypeId) . ',
                        ' . db_str($oSystemType->typeName) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `systemTypeId`=VALUES(`systemTypeId`),
                        `typeName`=VALUES(`typeName`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oSystemType->systemTypeId === null) {
            $oSystemType->systemTypeId = $oDb->insert_id;
        }
    }

    /**
     * delete item and all media
     *
     * @param System $oSystem
     *
     * @return Boolean
     */
    public static function deleteSystemType(SystemType $oSystemType)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oSystemType->isDeletable()) {

            $sQuery = "DELETE FROM `system_types` WHERE `systemTypeId` = " . db_int($oSystemType->systemTypeId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }



    /**
     * return SystemTypes filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array System cast(column as unsigned)
     */
    public static function getSystemTypesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`st`.`typeName`' => 'ASC', '`st`.`systemTypeId`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';


        # get by systemTypeId
        if (!empty($aFilter['systemTypeId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`st`.`systemTypeId` = ' . db_int($aFilter['systemTypeId']);
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`st`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ')';
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
                        `st`.*

                    FROM
                        `system_types` AS `st`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aSystemtypes = $oDb->query($sQuery, QRY_OBJECT, "SystemType");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aSystemtypes;
    }
}
