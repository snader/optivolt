<?php

class CountryTranslationManager
{
    /**
     * get CountryTranslation by CountryId
     *
     * @param int $iCountryTranslationId
     *
     * @return CountryTranslation
     */
    public static function getCountryTranslationByCountryTranslationId($iCountryTranslationId)
    {
        $sQuery = ' SELECT
                        `l`.*
                    FROM
                        `country_translations` AS `l`
                    WHERE
                        `l`.`countryTranslationId` = ' . db_int($iCountryTranslationId) . '
                    LIMIT 1
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CountryTranslation");
    }


    /**
     * get CountryTranslation by CountryId
     *
     * @param int $iCountryId
     *
     * @return CountryTranslation
     */
    public static function getCountryTranslationByCountryId($iCountryId, $iLocaleId = null)
    {
        if (!$iLocaleId) {
            $iLocaleId = Locales::locale();
        }

        $sQuery = ' SELECT
                        `l`.*
                    FROM
                        `country_translations` AS `l`
                    WHERE
                        `l`.`countryId` = ' . db_int($iCountryId) . '
                      AND
                        `l`.`localeId` = ' . db_int($iLocaleId). '
                    LIMIT 1
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CountryTranslation");
    }

    /**
     * save CountryTranslation object
     *
     * @param CountryTranslation $oCountryTranslation
     */
    public static function saveCountryTranslation(CountryTranslation $oCountryTranslation)
    {

        $sQuery = ' INSERT INTO `country_translations` (
                        `countryTranslationId`,
                        `countryId`,
                        `localeId`,
                        `name`
                    )
                    VALUES (
                        ' . db_int($oCountryTranslation->countryTranslationId) . ',
                        ' . db_str($oCountryTranslation->countryId) . ',
                        ' . db_str($oCountryTranslation->localeId) . ',
                        ' . db_str($oCountryTranslation->name) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `name`=VALUES(`name`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oCountryTranslation->countryTranslationId === null) {
            $oCountryTranslation->countryTranslationId = $oDb->insert_id;
        }
    }

    /**
     * delete CountryTranslation
     *
     * @param CountryTranslation $oCountryTranslation
     *
     * @return Boolean
     *
     */
    public static function deleteCountryTranslation(CountryTranslation $oCountryTranslation)
    {
        $oDb = DBConnections::get();

        if ($oCountryTranslation->isDeletable()) {
            $sQuery = 'DELETE FROM `country_translations` WHERE `countryTranslationId` = ' . db_int($oCountryTranslation->countryTranslationId) . ';';
            $oDb->query($sQuery, QRY_NORESULT);

            return $oDb->affected_rows > 0;
        }

        return false;
    }
}
