<?php

class ModuleRedirectManager
{

    /**
     * get redirectUrlPath by urlPath
     *
     * @param string $sUrlPath relative path of the url to search for
     * @param int    $iLanguageId
     *
     * @return object
     */
    public static function getModuleRedirectByUrlPath($sUrlPath, $iLanguageId = null)
    {
        if (empty($iLanguageId)) {
            $iLanguageId = Locales::language();
        }

        $sQuery = ' SELECT
                        `md`.*
                    FROM
                        `module_redirects` AS `md`
                    WHERE
                        `md`.`urlPath` = ' . db_str($sUrlPath) . '
                    AND
                        `md`.`languageId`=' . db_int($iLanguageId) . '
                    ORDER BY
                        `md`.`created` DESC
                    ;';

        $oDb        = DBConnections::get();
        $aRedirects = $oDb->query($sQuery, QRY_OBJECT, "ModuleRedirect");
        if (count($aRedirects) > 0) {
            return $aRedirects[0];
        }

        return null;
    }

    /**
     * delete moduleredirect by urlPath
     *
     * @param string $sUrlPath relative path of the url to search for
     * @param int    $iLanguageId
     *
     * @return object
     */
    public static function deleteModuleRedirectByUrlPath($sUrlPath, $iLanguageId)
    {
        $sQuery = ' DELETE FROM
                        `module_redirects`
                    WHERE
                        `urlPath` = ' . db_str($sUrlPath) . '
                    AND
                        `languageId`= ' . db_int($iLanguageId) . '
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

}

?>
