<?php

class ExternalSyncProviderManager
{

    /**
     * get the full ExternalSyncProvider object by id
     *
     * @param int $iExternalSyncProviderId
     *
     * @return ExternalSyncProvider
     */
    public static function getExternalSyncProviderById($iExternalSyncProviderId)
    {
        $sQuery = ' SELECT
                        `esp`.*
                    FROM
                        `external_sync_providers` AS `esp`
                    WHERE
                        `esp`.`externalSyncProviderId` = ' . db_int($iExternalSyncProviderId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "ExternalSyncProvider");
    }

    /**
     * get the full ExternalSyncProvider object by connector
     *
     * @param string $sConnector
     *
     * @return ExternalSyncProvider
     */
    public static function getExternalSyncProviderByConnector($sConnector)
    {
        $sQuery = ' SELECT
                        `esp`.*
                    FROM
                        `external_sync_providers` AS `esp`
                    WHERE
                        `esp`.`connector` = ' . db_str($sConnector) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "ExternalSyncProvider");
    }

    /**
     * save ExternalSyncProvider object
     *
     * @param ExternalSyncProvider $oExternalSyncProvider
     */
    public static function saveExternalSyncProvider(ExternalSyncProvider $oExternalSyncProvider)
    {

        # save item
        $sQuery = ' INSERT INTO `external_sync_providers` (
                        `externalSyncProviderId`,
                        `name`,
                        `item`,
                        `connector`,
                        `created`,
                        `createdBy`
                    )
                    VALUES (
                        ' . db_int($oExternalSyncProvider->externalSyncProviderId) . ',
                        ' . db_str($oExternalSyncProvider->name) . ',
                        ' . db_str($oExternalSyncProvider->item) . ',
                        ' . db_str($oExternalSyncProvider->connector) . ',
                        ' . 'NOW()' . ',
                        ' . db_str(getEditor()) . '                        
                    )
                    ON DUPLICATE KEY UPDATE
                        `name` = VALUES(`name`),
                        `item` = VALUES(`item`),
                        `connector` = VALUES(`connector`),
                        `modified` = ' . 'NOW()' . ',
                        `modifiedBy` = ' . db_str(getEditor()) . ' 
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oExternalSyncProvider->externalSyncProviderId === null) {
            $oExternalSyncProvider->externalSyncProviderId = $oDb->insert_id;
        }

    }

    /**
     * delete item and all media
     *
     * @param ExternalSyncProvider $oExternalSyncProvider
     *
     * @return Boolean
     */
    public static function deleteExternalSyncProvider(ExternalSyncProvider $oExternalSyncProvider)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oExternalSyncProvider->isDeletable()) {
            $sQuery = "DELETE FROM `external_sync_providers` WHERE `externalSyncProviderId` = " . db_int($oExternalSyncProvider->externalSyncProviderId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * return ExternalSyncProvider items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array ExternalSyncProvider
     */
    public static function getExternalSyncProvidersByFilter(
        array $aFilter = [],
        $iLimit = null,
        $iStart = 0,
        &$iFoundRows = false,
        $aOrderBy = [
            '`esp`.`externalSyncProviderId`' => 'DESC',
        ]
    ) {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        if (!empty($aFilter['item'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`esp`.`item` = ' . db_str($aFilter['item']);
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
                        `esp`.*
                    FROM
                        `external_sync_providers` AS `esp`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb                    = DBConnections::get();
        $aExternalSyncProviders = $oDb->query($sQuery, QRY_OBJECT, "ExternalSyncProvider");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aExternalSyncProviders;
    }
}
