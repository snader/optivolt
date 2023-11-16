<?php

class PageManager
{

    /**
     * get the full page object by id
     *
     * @param int $iPageId
     *
     * @return Page
     */
    public static function getPageById($iPageId)
    {
        $sQuery = ' SELECT
                        `p`.*
                    FROM
                        `pages` AS `p`
                    WHERE
                        `p`.`pageId` = ' . db_int($iPageId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Page");
    }

    /**
     * get page by name
     *
     * @param int $sName
     * @param int $iLanguageId
     *
     * @return Page
     */
    public static function getPageByName($sName, $iLanguageId = null)
    {
        if (empty($iLanguageId)) {
            $iLanguageId = Locales::language();
        }
        $sQuery = ' SELECT
                        `p`.*
                    FROM
                        `pages` AS `p`
                    WHERE
                        `p`.`name` = ' . db_str($sName) . '
                    AND
                        `p`.`languageId` = ' . db_int($iLanguageId) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'Page');
    }

    /**
     * get the controller by searching for url part
     *
     * @param string $sUrlPath relative path of the url
     *
     * @return string
     */
    public static function getControllerPathByUrlPath($sUrlPath, $iLanguageId = null)
    {
        if (empty($iLanguageId)) {
            $iLanguageId = Locales::language();
        }

        $sQuery = ' SELECT
                        `p`.`controllerPath`
                    FROM
                        `pages` AS `p`
                    WHERE
                        `p`.`urlPath` = ' . db_str($sUrlPath) . '
                    AND
                        `p`.`online` = 1
                    AND
                        `p`.`languageId` = ' . db_int($iLanguageId) . '
                    ;';

        $oDb   = DBConnections::get();
        $oPage = $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Page");
        if ($oPage) {
            return $oPage->getControllerPath();
        }

        return null;
    }

    /**
     * get the full page object by searching for url path
     *
     * @param string $sUrlPath     relative path of the url to search for
     * @param bool   $bCheckOnline only get online page?
     * @param int    $iLanguageId
     *
     * @return Page
     */
    public static function getPageByUrlPath($sUrlPath, $bCheckOnline = true, $iLanguageId = null)
    {
        if (empty($iLanguageId)) {
            $iLanguageId = Locales::language();
        }

        $sQuery = ' SELECT
                        `p`.*
                    FROM
                        `pages` AS `p`
                    WHERE
                        `p`.`urlPath` = ' . db_str($sUrlPath) . '
                    AND
                        `p`.`languageId` = ' . db_int($iLanguageId) . '
                    ' . ($bCheckOnline ? 'AND `p`.`online` = 1' : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Page");
    }

    /**
     * get redirectUrlPath by urlPath
     *
     * @param string $sUrlPath relative path of the url to search for
     *
     * @return object
     */
    public static function getRedirectUrlPathByUrlPath($sUrlPath)
    {
        $sQuery = ' SELECT
                        `p`.*
                    FROM
                        `pages` AS `p`
                    JOIN
                        `page_redirects` AS `pr` USING(`pageId`)
                    WHERE
                        `pr`.`urlPath` = ' . db_str($sUrlPath) . '
                    ORDER BY
                        `pr`.`created` DESC
                    ;';

        $oDb    = DBConnections::get();
        $aPages = $oDb->query($sQuery, QRY_OBJECT, "Page");
        if (count($aPages) > 0) {
            return $aPages[0]->getUrlPath();
        }

        return null;
    }

    /**
     * check if urlPath exists excluding given page
     *
     * @param string $sPathToCheck
     * @param int    $iPageId
     * @param int    $iLanguageId
     *
     * @return boolean
     */
    private static function urlPathExists($sPathToCheck, $iPageId, $iLanguageId)
    {
        $oPage = self::getPageByUrlPath($sPathToCheck, false, $iLanguageId);
        if ($oPage) {
            if ($oPage->pageId != $iPageId) {
                return true;
            }
        }

        return false;
    }

    /**
     * save Page object
     *
     * @param Page $oPage
     */
    public static function savePage(Page $oPage)
    {

        $bSaveSubs = false; //do not save all subs

        if (!$oPage->getLockUrlPath() || ($oPage->pageId === null && !$oPage->getUrlPath())) {

            # get 'new' pageUrlPath
            $sGeneratedUrlPath = $oPage->generateUrlPath();

            # set path to check to generatedPath
            $sPathToCheck = $sGeneratedUrlPath;
            $iT           = 0;

            # while urlPath is not unique, excluding this page, make unique
            while (self::urlPathExists($sPathToCheck, $oPage->pageId, $oPage->languageId)) {
                $iT++;
                $sPathToCheck = $sGeneratedUrlPath . "-$iT";
            }

            # path is last unique path
            $sGeneratedUrlPath = $sPathToCheck;

            # check 'new' url path with existing
            if ($oPage->pageId !== null && $oPage->getUrlPath() != $sGeneratedUrlPath) {
                self::savePageRedirect($oPage->pageId, $oPage->getUrlPath()); //save redirect record
                $bSaveSubs = true; //do save all subs after saving parent
            }

            $oPage->setUrlPath(); // generate path en set in object
        } else {
            // $sGeneratedUrlPath is `old` path (may not change with title, take current)
            $sGeneratedUrlPath = $oPage->getUrlPath();
        }

        $oPage->setLevel(); // recalculate level to page to be sure

        $oDb = new DBConnection();

        // check if module is installed
        $bAddFormProperty = false;
        if (moduleExists('forms') && $oDb->columnExists('pages', 'formId') && $oDb->columnExists('pages', 'hideFormManagement')) {
            $bAddFormProperty = true;
        }

        $sQuery = ' INSERT INTO `pages` (
                        `pageId`,
                        `languageId`,
                        `windowTitle`,
                        `metaKeywords`,
                        `metaDescription`,
                        `name`,
                        `title`,
                        `intro`,
                        `content`,
                        `shortTitle`,
                        `urlPath`,
                        `urlPart`,
                        `urlParameters`,
                        `controllerPath`,
                        `parentPageId`,
                        `online`,
                        `order`,
                        `onlineChangeable`,
                        `editable`,
                        `deletable`,
                        `inMenu`,
                        `inFooter`,
                        `indexable`,
                        `includeParentInUrlPath`,
                        `level`,
                        `customCanonical`,
                        `mayHaveSub`,
                        `lockUrlPath`,
                        `lockParent`,
                        `hideImageManagement`,
                        `hideFileManagement`,
                        `hideLinkManagement`,
                        `hideVideoLinkManagement`,
                        `hideBrandboxManagement`,
                        ' . ($bAddFormProperty ? '`formId`,' : '') . '
                        ' . ($bAddFormProperty ? '`hideFormManagement`,' : '') . '
                        `created`
                    )
                    VALUES (
                        ' . db_int($oPage->pageId) . ',
                        ' . db_int($oPage->languageId) . ',
                        ' . db_str($oPage->windowTitle) . ',
                        ' . db_str($oPage->metaKeywords) . ',
                        ' . db_str($oPage->metaDescription) . ',
                        ' . db_str($oPage->name) . ',
                        ' . db_str($oPage->title) . ',
                        ' . db_str($oPage->intro) . ',
                        ' . db_str($oPage->content) . ',
                        ' . db_str($oPage->shortTitle) . ',
                        ' . db_str($sGeneratedUrlPath) . ',
                        ' . db_str($oPage->getUrlPart()) . ',
                        ' . db_str($oPage->getUrlParameters()) . ',
                        ' . db_str($oPage->getControllerPath()) . ',
                        ' . db_str($oPage->parentPageId) . ',
                        ' . db_bool($oPage->online) . ',
                        ' . db_int($oPage->order) . ',
                        ' . db_bool($oPage->getOnlineChangeable()) . ',
                        ' . db_bool($oPage->getEditable()) . ',
                        ' . db_bool($oPage->getDeletable()) . ',
                        ' . db_bool($oPage->getInMenu()) . ',
                        ' . db_bool($oPage->getInFooter()) . ',
                        ' . db_bool($oPage->getIndexable()) . ',
                        ' . db_bool($oPage->getIncludeParentInUrlPath()) . ',
                        ' . db_int($oPage->level) . ',
                        ' . db_str($oPage->customCanonical) . ',
                        ' . db_bool($oPage->getMayHaveSub()) . ',
                        ' . db_bool($oPage->getLockUrlPath()) . ',
                        ' . db_bool($oPage->getLockParent()) . ',
                        ' . db_bool($oPage->getHideImageManagement()) . ',
                        ' . db_bool($oPage->getHideFileManagement()) . ',
                        ' . db_bool($oPage->getHideLinkManagement()) . ',
                        ' . db_bool($oPage->getHideVideoLinkManagement()) . ',
                        ' . db_bool($oPage->getHideBrandboxManagement()) . ',
                        ' . ($bAddFormProperty ? db_int($oPage->formId) . ',' : '') . '
                        ' . ($bAddFormProperty ? db_bool($oPage->getHideFormManagement()) . ',' : '') . '
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `languageId`=VALUES(`languageId`),
                        `windowTitle`=VALUES(`windowTitle`),
                        `metaKeywords`=VALUES(`metaKeywords`),
                        `metaDescription`=VALUES(`metaDescription`),
                        `name`=VALUES(`name`),
                        `title`=VALUES(`title`),
                        `intro`=VALUES(`intro`),
                        `content`=VALUES(`content`),
                        `shortTitle`=VALUES(`shortTitle`),
                        `urlPath`=VALUES(`urlPath`),
                        `urlPart`=VALUES(`urlPart`),
                        `urlParameters`=VALUES(`urlParameters`),
                        `controllerPath`=VALUES(`controllerPath`),
                        `parentPageId`=VALUES(`parentPageId`),
                        `online`=VALUES(`online`),
                        `order`=VALUES(`order`),
                        `onlineChangeable`=VALUES(`onlineChangeable`),
                        `editable`=VALUES(`editable`),
                        `deletable`=VALUES(`deletable`),
                        `inMenu`=VALUES(`inMenu`),
                        `inFooter`=VALUES(`inFooter`),
                        `indexable`=VALUES(`indexable`),
                        `includeParentInUrlPath`=VALUES(`includeParentInUrlPath`),
                        `level`=VALUES(`level`),
                        `customCanonical`=VALUES(`customCanonical`),
                        `mayHaveSub`=VALUES(`mayHaveSub`),
                        `lockUrlPath`=VALUES(`lockUrlPath`),
                        `lockParent`=VALUES(`lockParent`),
                        `hideImageManagement`=VALUES(`hideImageManagement`),
                        `hideFileManagement`=VALUES(`hideFileManagement`),
                        `hideLinkManagement`=VALUES(`hideLinkManagement`),
                        ' . ($bAddFormProperty ? '`formId`=VALUES(`formId`),' : '') . '
                        ' . ($bAddFormProperty ? '`hideFormManagement`=VALUES(`hideFormManagement`),' : '') . '
                        `hideVideoLinkManagement`=VALUES(`hideVideoLinkManagement`),
                        `hideBrandboxManagement`=VALUES(`hideBrandboxManagement`)
                    ;';

        $oDb->query($sQuery, QRY_NORESULT);

        if ($oPage->pageId === null) {
            $oPage->pageId = $oDb->insert_id;
        }

        if ($bSaveSubs && $oPage->hasSubPages()) {
            # also save all subs and their subs etc
            foreach ($oPage->getSubPages() AS $oSubPage) {
                self::savePage($oSubPage);
            }
        }
    }

    /**
     * save page redirect
     *
     * @param int    $iPageId
     * @param string $sUrlPath
     */
    public static function savePageRedirect($iPageId, $sUrlPath)
    {
        $sQuery = ' INSERT INTO `page_redirects` (
                        `pageId`,
                        `urlPath`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($iPageId) . ',
                        ' . db_str($sUrlPath) . ',
                        ' . 'NOW()' . '
                    )
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * delete page and all media
     *
     * @param Page $oPage
     *
     * @return boolean
     */
    public static function deletePage(Page $oPage)
    {

        $oDb = DBConnections::get();

        /* check if page exists and is deletable */
        if ($oPage->isDeletable()) {

            # get and delete images
            foreach ($oPage->getImages('all') AS $oImage) {
                ImageManager::deleteImage($oImage);
            }

            # get and delete files
            foreach ($oPage->getFiles('all') AS $oFile) {
                FileManager::deleteFile($oFile);
            }

            # get and delete links
            foreach ($oPage->getLinks('all') AS $oLink) {
                LinkManager::deleteLink($oLink);
            }

            # get and delete video links
            foreach ($oPage->getVideoLinks('all') AS $oVideoLink) {
                VideoLinkManager::deleteVideoLink($oVideoLink);
            }

            $sQuery = "DELETE FROM `pages` WHERE `pageId` = " . db_int($oPage->pageId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online by page id
     *
     * @param int  $bOnline
     * @param Page $oPage
     *
     * @return boolean
     */
    public static function updateOnlineByPage($bOnline, Page $oPage)
    {
        if ($oPage->isOnlineChangeable()) {
            $sQuery = ' UPDATE
                            `pages`
                        SET
                            `online` = ' . db_int($bOnline) . '
                        WHERE
                            `pageId` = ' . db_int($oPage->pageId) . '
                        ;';
            $oDb    = DBConnections::get();

            $oDb->query($sQuery, QRY_NORESULT);

            # check if somethinf happened
            return $oDb->affected_rows > 0;
        } else {
            return false;
        }
    }

    /**
     * return pages filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Page
     */
    public static function getPagesByFilter(
        array $aFilter = [],
        $iLimit = null,
        $iStart = 0,
        &$iFoundRows = false,
        $aOrderBy = [
            '`p`.`order`'  => 'ASC',
            '`p`.`pageId`' => 'ASC',
        ]
    ) {

        $sFrom  = '';
        $sWhere = '';
        $sFrom  = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`p`.`online` = 1';
            // check pages backwards recursively
            for ($iC = 2; $iC <= SettingManager::getSettingByName('pagesMaxLevels')->value; $iC++) {
                $sFrom  .= ($sFrom != '' ? ' ' : '') . 'LEFT JOIN `pages` AS `p' . $iC . '` ON `p' . ($iC) . '`.`pageId` = `p' . ($iC != 2 ? $iC - 1 : '') . '`.`parentPageId`';
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`p' . $iC . '`.`online` = 1 OR `p' . ($iC != 2 ? $iC - 1 : '') . '`.`parentPageId` IS NULL)';
            }
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`p`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        // get pages with specific level
        if (!empty($aFilter['level'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`p`.`level` = ' . db_int($aFilter['level']);
        }

        // get pages with parenPageId
        if (!empty($aFilter['parentPageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`p`.`parentPageId` = ' . db_int($aFilter['parentPageId']);
        }

        // get pages with indexable
        if (isset($aFilter['indexable'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`p`.`indexable` = ' . db_int($aFilter['indexable']);
        }

        // get pages with inMenu
        if (isset($aFilter['inMenu'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`p`.`inMenu` = ' . db_int($aFilter['inMenu']);
        }

        // get pages with inFooter
        if (isset($aFilter['inFooter'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`p`.`inFooter` = ' . db_int($aFilter['inFooter']);
        }

        // get pages with online
        if (isset($aFilter['online'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`p`.`online` = ' . db_int($aFilter['online']);
        }

        // get pages with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`p`.`modified`, `p`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
        }

        // get pages with specific level
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`p`.`title` LIKE ' . db_str('%' . $aFilter['q'] . '%');
        }

        if (!empty($aFilter['newsItemId'])) {
            $sFrom  .= 'JOIN `pages_news_items` AS `pni` ON `pni`.`pageId` = `p`.`pageId`';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`pni`.`newsItemId` = ' . db_int($aFilter['newsItemId']);
        }

        if (!empty($aFilter['NOTnewsItemId'])) {
            $sFrom  .= 'LEFT OUTER JOIN `pages_news_items` AS `pni` ON `pni`.`pageId` = `p`.`pageId` AND `pni`.`newsItemId` = ' . db_int($aFilter['NOTnewsItemId']);
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`pni`.`newsItemId` IS NULL)';
        }

        if (!empty($aFilter['whitePaperId'])) {
            $sFrom  .= 'JOIN `white_papers_pages` AS `wpp` ON `wpp`.`pageId` = `p`.`pageId`';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`wpp`.`whitePaperId` = ' . db_int($aFilter['whitePaperId']);
        }

        if (!empty($aFilter['NOTwhitePaperId'])) {
            $sFrom  .= 'LEFT OUTER JOIN `white_papers_pages` AS `wpp` ON `wpp`.`pageId` = `p`.`pageId` AND `wpp`.`pageId` = ' . db_int($aFilter['NOTwhitePaperId']);
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`wpp`.`pageId` IS NULL)';
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
                        `p`.*
                    FROM
                        `pages` AS `p`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb    = DBConnections::get();
        $aPages = $oDb->query($sQuery, QRY_OBJECT, "Page");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aPages;
    }

    /**
     * save connection between a page and an image
     *
     * @param int $iPageId
     * @param int $iImageId
     */
    public static function savePageImageRelation($iPageId, $iImageId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `pages_images`
                    (
                        `pageId`,
                        `imageId`
                    )
                    VALUES
                    (
                        ' . db_int($iPageId) . ',
                        ' . db_int($iImageId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get images for page by filter
     *
     * @param int   $iPageId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array Image
     */
    public static function getImagesByFilter($iPageId, array $aFilter = [], $iLimit = null)
    {
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `i`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `i`.*
                    FROM
                        `images` AS `i`
                    JOIN
                        `pages_images` AS `pi` USING (`imageId`)
                    WHERE
                        `pi`.`pageId` = ' . db_int($iPageId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `i`.`order` ASC, `i`.`imageId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'Image');
    }

    /**
     * save connection between a page and a file
     *
     * @param int $iPageId
     * @param int $iMediaId
     */
    public static function savePageFileRelation($iPageId, $iMediaId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `pages_files`
                    (
                        `pageId`,
                        `mediaId`
                    )
                    VALUES
                    (
                        ' . db_int($iPageId) . ',
                        ' . db_int($iMediaId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get files for page by filter
     *
     * @param int   $iPageId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array File
     */
    public static function getFilesByFilter($iPageId, array $aFilter = [], $iLimit = null)
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `m`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `m`.*,
                        `f`.*
                    FROM
                        `files` AS `f`
                    JOIN
                        `pages_files` AS `pf` USING (`mediaId`)
                    JOIN
                        `media` AS `m` USING (`mediaId`)
                    WHERE
                        `pf`.`pageId` = ' . db_int($iPageId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `m`.`order` ASC, `m`.`mediaId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'File');
    }

    /**
     * save connection between a page and a link
     *
     * @param int $iPageId
     * @param int $iMediaId
     */
    public static function savePageLinkRelation($iPageId, $iMediaId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `pages_links`
                    (
                        `pageId`,
                        `mediaId`
                    )
                    VALUES
                    (
                        ' . db_int($iPageId) . ',
                        ' . db_int($iMediaId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get links for page by filter
     *
     * @param int   $iPageId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array Link
     */
    public static function getLinksByFilter($iPageId, array $aFilter = [], $iLimit = null)
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `m`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `m`.*
                    FROM
                        `media` AS `m`
                    JOIN
                        `pages_links` AS `pl` USING (`mediaId`)
                    WHERE
                        `pl`.`pageId` = ' . db_int($iPageId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `m`.`order` ASC, `m`.`mediaId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'Link');
    }

    /**
     * save connection between a page and a VideoLink
     *
     * @param int $iPageId
     * @param int $iMediaId
     */
    public static function savePageVideoLinkRelation($iPageId, $iMediaId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `pages_video_links`
                    (
                        `pageId`,
                        `mediaId`
                    )
                    VALUES
                    (
                        ' . db_int($iPageId) . ',
                        ' . db_int($iMediaId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get video links for page by filter
     *
     * @param int   $iPageId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array VideoLink
     */
    public static function getVideoLinksByFilter($iPageId, array $aFilter = [], $iLimit = null)
    {
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `m`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `m`.*
                    FROM
                        `media` AS `m`
                    JOIN
                        `pages_video_links` AS `py` USING (`mediaId`)
                    WHERE
                        `py`.`pageId` = ' . db_int($iPageId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `m`.`order` ASC, `m`.`mediaId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';

        return VideoLinkManager::getVideoLinkByQuery($sQuery);
    }

    /**
     * get links for page by filter
     *
     * @param int   $iPageId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array Link
     */
    public static function getFormsByFilter($iPageId, array $aFilter = [], $iLimit = null)
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `f`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `f`.*
                    FROM
                        `forms` AS `f`
                    ' . $sWhere . '
                    ORDER BY
                        `f`.`formId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'Form');
    }

    /**
     * save connection between a page and a BB
     *
     * @param int $iPageId
     * @param int $iBrandboxItemId
     */
    public static function savePageBrandboxItemRelation($iPageId, $iBrandboxItemId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `pages_brandbox_items`
                    (
                        `pageId`,
                        `brandboxItemId`
                    )
                    VALUES
                    (
                        ' . db_int($iPageId) . ',
                        ' . db_int($iBrandboxItemId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * delete connection between a page and a BB
     */
    public static function deletePageBrandboxItemRelation($iPageId, $iBrandboxItemId)
    {
        $sQuery = ' DELETE FROM
                        `pages_brandbox_items`
                    WHERE
                        `pageId`=' . db_int($iPageId) . '
                    AND
                        `brandboxItemId`=' . db_int($iBrandboxItemId) . '
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get BBs for page by filter
     *
     * @param int   $iPageId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return \BrandboxItem[]
     */
    public static function getBrandboxItemsByFilter($iPageId, array $aFilter = [], $iLimit = null)
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `bb`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `bb`.*
                    FROM
                        `brandbox_items` AS `bb`
                    JOIN
                        `pages_brandbox_items` AS `pbb` USING (`brandboxItemId`)
                    WHERE
                        `pbb`.`pageId` = ' . db_int($iPageId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `bb`.`order` ASC,
                        `bb`.`brandboxItemId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'BrandboxItem');
    }

    public static function savePageNewsItemRelation(int $iPageId, int $iNewsItemId)
    : void {
        $sQuery = ' INSERT IGNORE INTO `pages_news_items`(
                        `pageId`,
                        `newsItemId`
                    )
                    VALUES (
                            ' . db_int($iPageId) . ', 
                            ' . db_int($iNewsItemId) . '
                            );';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    public static function deletePageNewsItemRelation(int $iPageId, int $iNewsItemId)
    : void {
        $sQuery = 'DELETE FROM `pages_news_items` WHERE `pageId` = ' . db_int($iPageId) . ' AND `newsItemId` = ' . db_int($iNewsItemId);
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * Save page usp relation
     *
     * @param int $iPageId
     * @param int $iUspId
     */
    public static function savePageUspRelation(int $iPageId, int $iUspId)
    : void {
        $sQuery = ' INSERT IGNORE INTO `pages_usps`(
                        `pageId`,
                        `uspId`
                    )
                    VALUES (
                            ' . db_int($iPageId) . ', 
                            ' . db_int($iUspId) . '
                            );';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * Delete page usp relation
     *
     * @param int $iPageId
     * @param int $iUspId
     */
    public static function deletePageUspRelation(int $iPageId, int $iUspId)
    : void {
        $sQuery = 'DELETE FROM `pages_usps` WHERE `pageId` = ' . db_int($iPageId) . ' AND `uspId` = ' . db_int($iUspId);
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }
}
