<?php

class CountryManager
{

    /**
     * get country by countryId
     *
     * @param int $iCountryId
     *
     * @return Country
     */
    public static function getCountryById($iCountryId)
    {
        $sQuery = ' SELECT
                        `c`.*
                    FROM
                        `countries` AS `c`
                    WHERE
                        `c`.`countryId` = ' . db_int($iCountryId) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Country");
    }

    /**
     * get country by code
     *
     * @param int $sCode
     *
     * @return Country
     */
    public static function getCountryByCode($sCode)
    {
        $sQuery = ' SELECT
                        `c`.*
                    FROM
                        `countries` AS `c`
                    WHERE
                        `c`.`code` = ' . db_str($sCode) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Country");
    }

    /**
     * save Country object
     *
     * @param Country $oCountry
     */
    public static function saveCountry(Country $oCountry)
    {

        $sQuery = ' INSERT INTO `countries` (
                        `countryId`,
                        `code`,
                        `nativeName`,
                        `currency`,
                        `languages`,
                        `code3`
                    )
                    VALUES (
                        ' . db_int($oCountry->countryId) . ',
                        ' . db_str($oCountry->code) . ',
                        ' . db_str($oCountry->nativeName) . ',
                        ' . db_str($oCountry->currency) . ',
                        ' . db_str($oCountry->languages) . ',
                        ' . db_str($oCountry->code3) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `code`=VALUES(`code`),
                        `nativeName`=VALUES(`nativeName`),
                        `currency`=VALUES(`currency`),
                        `languages`=VALUES(`languages`),
                        `code3`=VALUES(`code3`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oCountry->countryId === null) {
            $oCountry->countryId = $oDb->insert_id;
        }
    }

    /**
     * delete country
     *
     * @param Country $oCountry
     *
     * @return Boolean
     *
     */
    public static function deleteCountry(Country $oCountry)
    {
        $oDb = DBConnections::get();

        if ($oCountry->isDeletable()) {
            $sQuery = 'DELETE FROM `countries` WHERE `countryId` = ' . db_int($oCountry->countryId) . ';';
            $oDb->query($sQuery, QRY_NORESULT);

            return $oDb->affected_rows > 0;
        }

        return false;
    }

    /**
     * return countries filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return \Country[]
     */
    public static function getCountriesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`ct`.`name`' => 'ASC'])
    {
        $sFrom  = '';
        $sWhere = '';

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
            $sLimit .= $iLimit;
        }
        if ($sLimit !== '') {
            $sLimit = (is_numeric($iStart) ? $iStart . ',' : '0,') . $sLimit;
        }
        $sLimit = ($sLimit !== '' ? 'LIMIT ' : '') . $sLimit;

        $sQuery = ' SELECT ' . ($iFoundRows !== false ? 'SQL_CALC_FOUND_ROWS' : '') . '
                        `c`.*
                    FROM
                        `countries` AS `c`
                    INNER JOIN
                        `country_translations` AS `ct` ON `c`.`countryId` = `ct`.`countryId` AND `ct`.`localeId` = ' . db_int(Locales::locale()) . '
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `c`.`countryId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aCountries = $oDb->query($sQuery, QRY_OBJECT, "Country");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aCountries;
    }

}
