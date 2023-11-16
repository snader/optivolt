<?php

class LocaleManager
{

    /**
     * get locale by localeId
     *
     * @param int $iLocaleId
     *
     * @return ACMS\Locale
     */
    public static function getLocaleById($iLocaleId)
    {
        $sQuery = ' SELECT
                        `l`.*
                    FROM
                        `locales` AS `l`
                    WHERE
                        `l`.`localeId` = ' . db_int($iLocaleId) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "ACMS\Locale");
    }

    /**
     * get locale by urlFormat
     *
     * @param string $sURLFormat
     *
     * @return ACMS\Locale
     */
    public static function getLocaleByURLFormat($sURLFormat)
    {
        $sQuery = ' SELECT
                        `l`.*
                    FROM
                        `locales` AS `l`
                    WHERE
                        `l`.`urlFormat` = ' . db_str($sURLFormat) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "ACMS\Locale");
    }

    /**
     * check if urlFormat exists excluding given locale
     *
     * @param string $sFormat
     * @param int    $iLocaleId
     *
     * @return boolean
     */
    public static function urlFormatExists($sFormat, $iLocaleId)
    {
        $oLocale = self::getLocaleByURLFormat($sFormat, false);
        if ($oLocale) {
            if ($oLocale->localeId != $iLocaleId) {
                return true;
            }
        }

        return false;
    }

    /**
     * check if language and country exists excluding given locale
     *
     * @param int $iLanguageId
     * @param int $iCountryId
     * @param int $iLocaleId
     *
     * @return boolean
     */
    public static function languageAndCountryExists($iLanguageId, $iCountryId, $iLocaleId)
    {
        $oLocale = self::getLocaleByLanguageIdAndCountryId($iLanguageId, $iCountryId);
        if ($oLocale) {
            if ($oLocale->localeId != $iLocaleId) {
                return true;
            }
        }

        return false;
    }

    /**
     * get locale by language and country
     *
     * @param int $iLanguageId
     * @param int $iCountryId
     *
     * @return ACMS\Locale
     */
    public static function getLocaleByLanguageIdAndCountryId($iLanguageId, $iCountryId)
    {
        $sQuery = ' SELECT
                        `l`.*
                    FROM
                        `locales` AS `l`
                    WHERE
                        `l`.`languageId` = ' . db_str($iLanguageId) . '
                    AND
                        `l`.`countryId` = ' . db_str($iCountryId) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "ACMS\Locale");
    }

    /**
     * save Locale object
     *
     * @global type        $oCurrentUser
     *
     * @param \ACMS\Locale $oLocale
     */
    public static function saveLocale(\ACMS\Locale $oLocale)
    {
        $oCurrentUser = UserManager::getCurrentUser();
        $oDb          = DBConnections::get();
        /* new locale */
        if (!$oLocale->localeId) {
            $oLocale->createdBy = $oCurrentUser->name;
            $sQuery             = ' INSERT INTO
                            `locales`
                        (
                            `languageId`,
                            `countryId`,
                            `domain`,
                            `subdomain`,
                            `prefix1`,
                            `prefix2`,
                            `urlFormat`,
                            `online`,
                            `order`,
                            `dateFormat`,
                            `created`,
                            `createdBy`
                        )
                        VALUES
                        (
                            ' . db_int($oLocale->languageId) . ',
                            ' . db_int($oLocale->countryId) . ',
                            ' . db_str($oLocale->domain) . ',
                            ' . db_str($oLocale->subdomain) . ',
                            ' . db_str($oLocale->prefix1) . ',
                            ' . db_str($oLocale->prefix2) . ',
                            ' . db_str($oLocale->getURLFormat(true)) . ',
                            ' . db_int($oLocale->online) . ',
                            ' . db_int($oLocale->order) . ',
                            ' . db_str($oLocale->dateFormat) . ',
                            NOW(),
                            ' . db_str($oLocale->createdBy) . '
                        )
                        ;';

            $oDb->query($sQuery, QRY_NORESULT);

            /* get new localeId */
            $oLocale->localeId = $oDb->insert_id;
        } else {
            $oLocale->modifiedBy = $oCurrentUser ? $oCurrentUser->name : 'System';
            $sQuery              = ' UPDATE
                            `locales`
                        SET
                            `languageId` = ' . db_int($oLocale->languageId) . ',
                            `countryId` = ' . db_int($oLocale->countryId) . ',
                            `domain` = ' . db_str($oLocale->domain) . ',
                            `subdomain` = ' . db_str($oLocale->subdomain) . ',
                            `prefix1` = ' . db_str($oLocale->prefix1) . ',
                            `prefix2` = ' . db_str($oLocale->prefix2) . ',
                            `urlFormat` = ' . db_str($oLocale->getURLFormat(true)) . ',
                            `online` = ' . db_int($oLocale->online) . ',
                            `order` = ' . db_int($oLocale->order) . ',
                            `dateFormat` = ' . db_str($oLocale->dateFormat) . ',
                            `modified` = NOW(),
                            `modifiedBy` = ' . db_str($oLocale->modifiedBy) . '
                        WHERE
                            `localeId` = ' . db_int($oLocale->localeId) . '
                        ;';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    /**
     * delete locale
     *
     * @param \ACMS\Locale $oLocale
     *
     * @return Boolean
     *
     */
    public static function deleteLocale(\ACMS\Locale $oLocale)
    {
        $oDb = DBConnections::get();

        if ($oLocale->isDeletable()) {
            $sQuery = 'DELETE FROM `locales` WHERE `localeId` = ' . db_int($oLocale->localeId) . ';';
            $oDb->query($sQuery, QRY_NORESULT);

            return $oDb->affected_rows > 0;
        }

        return false;
    }

    /**
     * return locales filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return ACMS\Locale|ACMS\Locale[]
     */
    public static function getLocalesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`l`.`order`' => 'ASC'])
    {
        $sFrom  = '';
        $sWhere = '';

        // no show all? only show online items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`l`.`online` = 1';
        }

        // search for with languageId
        if (!empty($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`l`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        if (!empty($aFilter['NOTlanguageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`l`.`languageId` != ' . db_int($aFilter['NOTlanguageId']);
        }

        // search for countryId
        if (!empty($aFilter['countryId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`l`.`countryId` = ' . db_int($aFilter['countryId']);
        }

        // search for domain
        if (!empty($aFilter['domain'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`l`.`domain` = ' . db_str($aFilter['domain']);
        }

        // search for subdomain
        if (!empty($aFilter['subdomain'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`l`.`subdomain` = ' . db_str($aFilter['subdomain']);
        }

        // search for prefix1
        if (!empty($aFilter['prefix1'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`l`.`prefix1` = ' . db_str($aFilter['prefix1']);
        }

        // search for prefix2
        if (!empty($aFilter['prefix2'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`l`.`prefix2` = ' . db_str($aFilter['prefix2']);
        }

        // search for urlFormat
        if (!empty($aFilter['urlFormat'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`l`.`urlFormat` = ' . db_str($aFilter['urlFormat']);
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
                        `locales` AS `l`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `l`.`localeId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb      = DBConnections::get();
        $aLocales = $oDb->query($sQuery, $iLimit <> 1 ? QRY_OBJECT : QRY_UNIQUE_OBJECT, "ACMS\Locale");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aLocales;
    }

    /**
     * update online by news item id
     *
     * @param int $bOnline
     * @param int $iLocaleId
     *
     * @return boolean
     */
    public static function updateOnlineByLocaleId($bOnline, $iLocaleId)
    {
        $sQuery = ' UPDATE
                        `locales`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `localeId` = ' . db_int($iLocaleId) . '
                    ;';
        $oDb    = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

}
