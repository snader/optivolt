<?php

class SearchRedirectManager
{

    /**
     * get a SearchRedirect by id
     *
     * @param int $iSearchRedirectId
     *
     * @return SearchRedirect
     */
    public static function getSearchRedirectById($iSearchRedirectId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `search_redirects`
                    WHERE
                        `searchId` = ' . db_int($iSearchRedirectId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "SearchRedirect");
    }

    /**
     * get a SearchRedirect by searchword
     *
     * @param string $sSearchWord
     *
     * @return SearchRedirect
     */
    public static function getSearchRedirectBySearchWord($sSearchWord)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `search_redirects`
                    WHERE
                        `searchword` = ' . db_str($sSearchWord) . '
                    AND
                        `languageId` = ' . db_int(Locales::language()) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "SearchRedirect");
    }

    /**
     * return search redirects filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array SearchRedirect
     */
    public static function getSearchRedirectsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`bi`.`hits`' => 'DESC', '`bi`.`searchId`' => 'DESC'])
    {
        $sFrom  = '';
        $sWhere = '';

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`bi`.`searchword` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ')';
        }
        # show only with link or not
        if (isset($aFilter['withlink']) && is_numeric($aFilter['withlink'])) {
            if ($aFilter['withlink']) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`bi`.`pageId` IS NOT NULL OR `bi`.`newsItemId` IS NOT NULL OR `bi`.`photoAlbumId` IS NOT NULL OR `bi`.`catalogProductId` IS NOT NULL)';
            } else {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`bi`.`pageId` IS NULL AND `bi`.`newsItemId` IS NULL AND `bi`.`photoAlbumId` IS NULL AND `bi`.`catalogProductId` IS NULL)';
            }

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
                        `bi`.*
                    FROM
                        `search_redirects` AS `bi`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb              = DBConnections::get();
        $aSearchRedirects = $oDb->query($sQuery, QRY_OBJECT, "SearchRedirect");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aSearchRedirects;
    }

    /**
     * save a SearchRedirect
     *
     * @param SearchRedirect $oSearchRedirect
     */
    public static function saveSearchRedirect(SearchRedirect $oSearchRedirect)
    {
        $sQuery = ' INSERT INTO `search_redirects`(
                        `searchId`,
                        `searchword`,
                        `pageId`,
                        `newsItemId`,
                        `photoAlbumId`,
                        `catalogProductId`,
                        `hits`,
                        `languageId`
                    )
                    VALUES (
                        ' . db_int($oSearchRedirect->searchId) . ',
                        ' . db_str($oSearchRedirect->searchword) . ',
                        ' . db_int($oSearchRedirect->pageId) . ',
                        ' . db_int($oSearchRedirect->newsItemId) . ',
                        ' . db_int($oSearchRedirect->photoAlbumId) . ',
                        ' . db_int($oSearchRedirect->catalogProductId) . ',
                        ' . db_int($oSearchRedirect->hits) . ',
                        ' . db_int($oSearchRedirect->languageId) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `searchword`=VALUES(`searchword`),
                        `pageId`=VALUES(`pageId`),
                        `newsItemId`=VALUES(`newsItemId`),
                        `photoAlbumId`=VALUES(`photoAlbumId`),
                        `catalogProductId`=VALUES(`catalogProductId`),
                        `hits`=VALUES(`hits`),
                        `languageId`=VALUES(`languageId`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oSearchRedirect->searchId === null) {
            $oSearchRedirect->searchId = $oDb->insert_id;
        }
    }

    /**
     * delete a SearchRedirect
     *
     * @param SearchRedirect $oSearchRedirect
     *
     * @return bool true
     */
    public static function deleteSearchRedirect(SearchRedirect $oSearchRedirect)
    {

        # delete object
        $sQuery = ' DELETE FROM
                        `search_redirects`
                    WHERE
                        `searchId` = ' . db_int($oSearchRedirect->searchId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

}

?>