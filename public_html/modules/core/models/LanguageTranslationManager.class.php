<?php

class LanguageTranslationManager
{
    /**
     * get LanguageTranslation by LanguageId
     *
     * @param int $iLanguageTranslationId
     *
     * @return LanguageTranslation
     */
    public static function getLanguageTranslationByLanguageTranslationId($iLanguageTranslationId)
    {
        $sQuery = ' SELECT
                        `l`.*
                    FROM
                        `language_translations` AS `l`
                    WHERE
                        `l`.`languageTranslationId` = ' . db_int($iLanguageTranslationId) . '
                    LIMIT 1
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "LanguageTranslation");
    }


    /**
     * get LanguageTranslation by LanguageId
     *
     * @param int $iLanguageId
     *
     * @return LanguageTranslation
     */
    public static function getLanguageTranslationByLanguageId($iLanguageId, $iLocaleId = null)
    {
        if (!$iLocaleId) {
            $iLocaleId = Locales::locale();
        }

        $sQuery = ' SELECT
                        `l`.*
                    FROM
                        `language_translations` AS `l`
                    WHERE
                        `l`.`languageId` = ' . db_int($iLanguageId) . '
                      AND
                        `l`.`localeId` = ' . db_int($iLocaleId). '
                    LIMIT 1
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "LanguageTranslation");
    }

    /**
     * save LanguageTranslation object
     *
     * @param LanguageTranslation $oLanguageTranslation
     */
    public static function saveLanguageTranslation(LanguageTranslation $oLanguageTranslation)
    {

        $sQuery = ' INSERT INTO `language_translations` (
                        `languageTranslationId`,
                        `languageId`,
                        `localeId`,
                        `name`
                    )
                    VALUES (
                        ' . db_int($oLanguageTranslation->languageTranslationId) . ',
                        ' . db_str($oLanguageTranslation->languageId) . ',
                        ' . db_str($oLanguageTranslation->localeId) . ',
                        ' . db_str($oLanguageTranslation->name) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `name`=VALUES(`name`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oLanguageTranslation->languageTranslationId === null) {
            $oLanguageTranslation->languageTranslationId = $oDb->insert_id;
        }
    }

    /**
     * delete LanguageTranslation
     *
     * @param LanguageTranslation $oLanguageTranslation
     *
     * @return Boolean
     *
     */
    public static function deleteLanguageTranslation(LanguageTranslation $oLanguageTranslation)
    {
        $oDb = DBConnections::get();

        if ($oLanguageTranslation->isDeletable()) {
            $sQuery = 'DELETE FROM `language_translations` WHERE `languageTranslationId` = ' . db_int($oLanguageTranslation->languageTranslationId) . ';';
            $oDb->query($sQuery, QRY_NORESULT);

            return $oDb->affected_rows > 0;
        }

        return false;
    }
}
