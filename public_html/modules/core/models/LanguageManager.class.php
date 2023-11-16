<?php

class LanguageManager
{

    /**
     * get language by languageId
     *
     * @param int $iLanguageId
     *
     * @return Language
     */
    public static function getLanguageById($iLanguageId)
    {
        $sQuery = ' SELECT
                        `l`.*
                    FROM
                        `languages` AS `l`
                    WHERE
                        `l`.`languageId` = ' . db_int($iLanguageId) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Language");
    }

    /**
     * save Language object
     *
     * @param Language $oLanguage
     */
    public static function saveLanguage(Language $oLanguage)
    {

        $sQuery = ' INSERT INTO `languages` (
                        `languageId`,
                        `code`,
                        `nativeName`,
                        `rtl`,
                        `code3`
                    )
                    VALUES (
                        ' . db_int($oLanguage->languageId) . ',
                        ' . db_str($oLanguage->code) . ',
                        ' . db_str($oLanguage->nativeName) . ',
                        ' . db_int($oLanguage->rtl) . ',
                        ' . db_str($oLanguage->code3) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `code`=VALUES(`code`),
                        `nativeName`=VALUES(`nativeName`),
                        `rtl`=VALUES(`rtl`),
                        `code3`=VALUES(`code3`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oLanguage->languageId === null) {
            $oLanguage->languageId = $oDb->insert_id;
        }
    }

    /**
     * delete language
     *
     * @param Language $oLanguage
     *
     * @return Boolean
     *
     */
    public static function deleteLanguage(Language $oLanguage)
    {
        $oDb = DBConnections::get();

        if ($oLanguage->isDeletable()) {
            $sQuery = 'DELETE FROM `languages` WHERE `languageId` = ' . db_int($oLanguage->languageId) . ';';
            $oDb->query($sQuery, QRY_NORESULT);

            return $oDb->affected_rows > 0;
        }

        return false;
    }

    /**
     * return languages filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return \Language[]
     */
    public static function getLanguagesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`lt`.`name`' => 'ASC'])
    {
        $sFrom  = '';
        $sWhere = '';

        # language has locale
        if (!empty($aFilter['hasLocale'])) {
            $sFrom .= 'JOIN `locales` as `lc` ON `lc`.`languageId` = `l`.`languageId`';
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
            $sLimit .= $iLimit;
        }
        if ($sLimit !== '') {
            $sLimit = (is_numeric($iStart) ? $iStart . ',' : '0,') . $sLimit;
        }
        $sLimit = ($sLimit !== '' ? 'LIMIT ' : '') . $sLimit;

        $sQuery = ' SELECT ' . ($iFoundRows !== false ? 'SQL_CALC_FOUND_ROWS' : '') . '
                        `l`.*
                    FROM
                        `languages` AS `l`
                    INNER JOIN
                        `language_translations` AS `lt` ON `l`.`languageId` = `lt`.`languageId` AND `lt`.`localeId` = ' . db_int(Locales::locale()) . '
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `l`.`languageId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aLanguages = $oDb->query($sQuery, QRY_OBJECT, "Language");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aLanguages;
    }

}
