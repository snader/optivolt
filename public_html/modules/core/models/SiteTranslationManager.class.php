<?php

/**
 * Manages all the text labels used in the admin panel
 */
class SiteTranslationManager
{

    /**
     *
     * @param string $iLanguageId
     * @param string $sLabel
     *
     * @return SiteTranslation
     */
    public static function getTranslationByLabel($iLanguageId, $sLabel)
    {
        $sQuery = ' SELECT
                        `st`.*
                    FROM
                        `site_translations` AS `st`
                    WHERE
                        `st`.`languageId` = ' . db_str($iLanguageId) . '
                    AND
                        `st`.`label` = ' . db_str($sLabel) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'SiteTranslation');
    }

    /**
     *
     * @param string $iSiteTranslationId
     *
     * @return SiteTranslation
     */
    public static function getTranslationById($iSiteTranslationId)
    {
        $sQuery = ' SELECT
                        `st`.*
                    FROM
                        `site_translations` AS `st`
                    WHERE
                        `st`.`siteTranslationId` = ' . db_str($iSiteTranslationId) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'SiteTranslation');
    }

    /**
     *
     * @global User  $oCurrentUser
     *
     * @param string $iLanguageId
     * @param string $sLabel
     * @param string $sText
     */
    public static function saveTranslation(SiteTranslation $oSiteTranslation)
    {
        $oCurrentUser = UserManager::getCurrentUser();
        $oDb          = DBConnections::get();

        if (empty($oSiteTranslation->siteTranslationId)) {
            $oSiteTranslation->createdBy = $oCurrentUser->name;
            $sQuery                      = ' INSERT INTO `site_translations` (
                        `languageId`,
                        `label`,
                        `text`,
                        `editable`,
                        `created`,
                        `createdBy`
                    ) VALUES (
                        ' . db_int($oSiteTranslation->languageId) . ',
                        ' . db_str($oSiteTranslation->label) . ',
                        ' . db_str($oSiteTranslation->text) . ',
                        ' . db_int($oSiteTranslation->editable) . ',
                        NOW(),
                        ' . db_str($oSiteTranslation->createdBy) . '
                    );';
            $oDb->query($sQuery, QRY_NORESULT);
            $oSiteTranslation->siteTranslationId = $oDb->insert_id;
        } else {
            $oSiteTranslation->modifiedBy = $oCurrentUser->name;
            $sQuery                       = 'UPDATE
                            `site_translations`
                        SET
                            `languageId` = ' . db_int($oSiteTranslation->languageId) . ',
                            `label` = ' . db_str($oSiteTranslation->label) . ',
                            `text` = ' . db_str($oSiteTranslation->text) . ',
                            `editable` = ' . db_int($oSiteTranslation->editable) . ',
                            `modified` = NOW(),
                            `modifiedBy` = ' . db_str($oSiteTranslation->modifiedBy) . '
                        WHERE
                            `siteTranslationId` = ' . db_int($oSiteTranslation->siteTranslationId) . '
                        ;';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    /**
     * return site translations filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array SiteTranslation
     */
    public static function getTranslationsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`st`.`siteTranslationId`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        // search for languageId
        if (!empty($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`st`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        // search for prefix
        if (!empty($aFilter['prefix'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`st`.`label` LIKE ' . db_str($aFilter['prefix'] . '%');
        }

        // search for label
        if (!empty($aFilter['label'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`st`.`label` LIKE ' . db_str('%' . $aFilter['label'] . '%');
        }

        // search for label
        if (!empty($aFilter['label%'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`st`.`label` LIKE ' . db_str($aFilter['label%'] . '%');
        }

        // search for text
        if (!empty($aFilter['text'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`st`.`text` LIKE ' . db_str('%' . $aFilter['text'] . '%');
        }

        if (isset($aFilter['showEditable']) && $aFilter['showEditable'] == 1) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`st`.`editable` = 1 OR `st`.`editable` = 0)';
        } else {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`st`.`editable` = 1';
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
                        `st`.*
                    FROM
                        `site_translations` AS `st`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb               = DBConnections::get();
        $aSiteTranslations = $oDb->query($sQuery, QRY_OBJECT, "SiteTranslation");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aSiteTranslations;
    }

    /**
     * get all site translations existing and non existing based on the total of unique labels
     *
     * @param int     $iLanguageId
     * @param boolean $bShowEmptyOnly
     *
     * @return array
     */
    public static function getFullTranslationForLanguage($iLanguageId, $bShowEmptyOnly = false)
    {
        $sQuery = ' SELECT
                        DISTINCT `st`.`label`,
                        `stL`.`siteTranslationId`,
                        ' . db_int($iLanguageId) . ' AS `languageId`,
                        `stL`.`text`
                    FROM
                        `site_translations` AS `st`
                    LEFT JOIN
                        (
                            SELECT
                                *
                            FROM
                                `site_translations`
                            WHERE
                                `languageId` = ' . db_int($iLanguageId) . '
                        ) AS `stL` ON `stL`.`label` = `st`.`label`
                    ' . ($bShowEmptyOnly ? 'WHERE `stL`.`text` IS NULL' : '') . '
                    ORDER BY
                        IF(`stL`.`text` IS NULL, 0, 1) ASC,
                        `st`.`label` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "SiteTranslation");
    }

    /**
     * delete site translation
     *
     * @param SiteTranslation $oSiteTranslation
     *
     * @return boolean
     */
    public static function deleteTranslation(SiteTranslation $oSiteTranslation)
    {
        $oDb = DBConnections::get();

        $sQuery = "DELETE FROM `site_translations` WHERE `siteTranslationId` = " . db_int($oSiteTranslation->siteTranslationId) . ";";
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

}

?>