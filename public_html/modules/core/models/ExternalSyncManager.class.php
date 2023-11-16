<?php

class ExternalSyncManager
{

    /**
     * get the full ExternalSync object by id
     *
     * @param int $iExternalSyncId
     *
     * @return ExternalSync
     */
    public static function getExternalSyncById($iExternalSyncId)
    {
        $sQuery = ' SELECT
                        `es`.*
                    FROM
                        `external_syncs` AS `es`
                    WHERE
                        `es`.`externalSyncId` = ' . db_int($iExternalSyncId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "ExternalSync");
    }

    /**
     * get the full ExternalSync object by relation and connector is
     *
     * @param string $sItem
     * @param int    $iItemId
     * @param int    $iExternalSyncProviderId
     *
     * @return ExternalSync
     */
    public static function getExternalSyncByItemAndProvider($sItem, $iItemId, $iExternalSyncProviderId)
    {
        $sQuery = ' SELECT
                        `es`.*
                    FROM
                        `external_syncs` AS `es`
                    WHERE
                        `es`.`item` = ' . db_str($sItem) . '
                    AND
                        `es`.`itemId` = ' . db_int($iItemId) . '
                    AND
                        `es`.`externalSyncProviderId` = ' . db_int($iExternalSyncProviderId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "ExternalSync");
    }

    /**
     * save ExternalSync object
     *
     * @param ExternalSync $oExternalSync
     */
    public static function saveExternalSync(ExternalSync $oExternalSync)
    {

        # save item
        $sQuery = ' INSERT INTO `external_syncs` (
                        `externalSyncId`,
                        `item`,
                        `itemId`,
                        `externalSyncProviderId`,
                        `lastSynced`,
                        `synced`,
                        `lastError`,
                        `externalId`,
                        `created`,
                        `createdBy`
                    )
                    VALUES (
                        ' . db_int($oExternalSync->externalSyncId) . ',
                        ' . db_str($oExternalSync->item) . ',
                        ' . db_int($oExternalSync->itemId) . ',
                        ' . db_int($oExternalSync->externalSyncProviderId) . ',
                        ' . db_date($oExternalSync->lastSynced) . ',
                        ' . db_int($oExternalSync->synced) . ',
                        ' . db_str($oExternalSync->lastError) . ',
                        ' . db_str($oExternalSync->externalId) . ',
                        ' . 'NOW()' . ',
                        ' . db_str(getEditor()) . '                        
                    )
                    ON DUPLICATE KEY UPDATE
                        `item` = VALUES(`item`),
                        `itemId` = VALUES(`itemId`),
                        `externalSyncProviderId` = VALUES(`externalSyncProviderId`),
                        `lastSynced` = VALUES(`lastSynced`),
                        `synced` = VALUES(`synced`),
                        `lastError` = VALUES(`lastError`),
                        `externalId` = VALUES(`externalId`),
                        `modified` = ' . 'NOW()' . ',
                        `modifiedBy` = ' . db_str(getEditor()) . ' 
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oExternalSync->externalSyncId === null) {
            $oExternalSync->externalSyncId = $oDb->insert_id;
        }

    }

    /**
     * delete item and all media
     *
     * @param ExternalSync $oExternalSync
     *
     * @return Boolean
     */
    public static function deleteExternalSync(ExternalSync $oExternalSync)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oExternalSync->isDeletable()) {
            $sQuery = "DELETE FROM `external_syncs` WHERE `externalSyncId` = " . db_int($oExternalSync->externalSyncId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * return ExternalSync items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array ExternalSync
     */
    public static function getExternalSyncsByFilter(
        array $aFilter = [],
        $iLimit = null,
        $iStart = 0,
        &$iFoundRows = false,
        $aOrderBy = [
            '`es`.`externalSyncId`' => 'DESC',
        ]
    ) {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

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
                        `es`.*
                    FROM
                        `external_syncs` AS `es`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb            = DBConnections::get();
        $aExternalSyncs = $oDb->query($sQuery, QRY_OBJECT, "ExternalSync");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aExternalSyncs;
    }

    /**
     * reset synced to let the system know that something changed and that the relation needs to be updated
     *
     * @param string $sItem
     * @param int    $iItemId
     * @param int    $iExternalSyncProviderId
     */
    public static function resetSynced($sItem, $iItemId, $iExternalSyncProviderId)
    {
        $sQuery = ' UPDATE
                        `external_syncs`
                    SET
                        `synced` = 0
                    WHERE
                        `item` = ' . db_str($sItem) . '
                    AND
                        `itemId` = ' . db_int($iItemId) . '
                    AND
                        `externalSyncProviderId` = ' . db_int($iExternalSyncProviderId) . '
                    ';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * update date when last sync happened
     *
     * @param string $sItem
     * @param int    $iItemId
     * @param int    $iExternalSyncProviderId
     */
    public static function updateLastSynced($sItem, $iItemId, $iExternalSyncProviderId)
    {
        $sQuery = ' UPDATE
                        `external_syncs`
                    SET
                        `lastSynced` = NOW(),
                        `synced` = 1,
                        `lastError` = NULL
                    WHERE
                        `item` = ' . db_str($sItem) . '
                    AND
                        `itemId` = ' . db_int($iItemId) . '
                    AND
                        `externalSyncProviderId` = ' . db_int($iExternalSyncProviderId) . '
                    ';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * set the external id for a specific Relation
     *
     * @param string $sItem
     * @param int    $iItemId
     * @param int    $iExternalSyncProviderId
     * @param string $sExternalId
     */
    public static function saveExternalId($sExternalId, $sItem, $iItemId, $iExternalSyncProviderId)
    {
        $sQuery = ' UPDATE
                        `external_syncs`
                    SET
                        `externalId` = ' . db_str($sExternalId) . '
                    WHERE
                        `item` = ' . db_str($sItem) . '
                    AND
                        `itemId` = ' . db_int($iItemId) . '
                    AND
                        `externalSyncProviderId` = ' . db_int($iExternalSyncProviderId) . '
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * save an sync error that occurred for a Relation
     *
     * @param string $sItem
     * @param int    $iItemId
     * @param int    $iExternalSyncProviderId
     * @param string $sError
     */
    public static function saveLastError($sError, $sItem, $iItemId, $iExternalSyncProviderId)
    {
        $sQuery = ' UPDATE
                        `external_syncs`
                    SET
                        `lastError` = ' . db_str($sError) . '
                    WHERE
                        `item` = ' . db_str($sItem) . '
                    AND
                        `itemId` = ' . db_int($iItemId) . '
                    AND
                        `externalSyncProviderId` = ' . db_int($iExternalSyncProviderId) . '
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

}
