<?php

// check folders existance and writing rights
$aCheckRightFolders = [
];

// check dependencies
$aDependencyModules = [
    'core',
    'pages',
];

$aNeededAdminControllerRoutes = [
    'searchredirect' => [
        'module'     => 'search',
        'controller' => 'search_redirect',
    ],
];

$aNeededClassRoutes = [
    'Search'                => [
        'module' => 'search',
    ],
    'SearchManager'         => [
        'module' => 'search',
    ],
    'SearchRedirectManager' => [
        'module' => 'search',
    ],
    'SearchRedirect'        => [
        'module' => 'search',
    ],
];

$aNeededSiteControllerRoutes = [
    'zoeken' => [
        'module'     => 'search',
        'controller' => 'search',
    ],
];

$aNeededModulesForMenu = [
    [
        'name'             => 'searchredirect',
        'icon'             => 'fa-search',
        'linkName'         => 'search_redirect',
        'parentModuleName' => 'instellingen',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'search_redirect_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'searchredirect_all_items', 'text' => 'Alle zoekredirects'],
        ['label' => 'searchredirect_add', 'text' => 'Zoekredirect toevoegen'],
        ['label' => 'searchredirect_edit', 'text' => 'Zoekredirect bewerken'],
        ['label' => 'searchredirect_no_items', 'text' => 'Er zijn geen zoekredirects om weer te geven'],
        ['label' => 'searchredirect_back_overview', 'text' => 'Keer terug naar het zoekredirect overzicht'],
        ['label' => 'searchredirect_item', 'text' => 'Zoekredirect'],
        ['label' => 'searchredirect_link_page', 'text' => 'Link pagina'],
        ['label' => 'searchredirect_link_news', 'text' => 'Link nieuwsatrikel'],
        ['label' => 'searchredirect_link_photoalbum', 'text' => 'Link fotoalbum'],
        ['label' => 'searchredirect_link_product', 'text' => 'Link product'],
        ['label' => 'searchredirect_item_saved', 'text' => 'Zoekredirect is opgeslagen'],
        ['label' => 'searchredirect_item_not_saved', 'text' => 'Zoekredirect is niet opgeslagen, niet alles is (juist) ingevuld'],
        ['label' => 'searchredirect_item_deleted', 'text' => 'Zoekredirect is verwijderd'],
        ['label' => 'searchredirect_item_not_deleted', 'text' => 'Zoekredirect kan niet verwijderd worden'],
        ['label' => 'searchredirect_item_edition', 'text' => 'Zoekredirect bewerken'],
        ['label' => 'search_redirect', 'text' => 'Zoekredirects'],
        ['label' => 'searchredirect_delete', 'text' => 'Verwijder zoekredirect'],
        ['label' => 'searchredirect_no_searchword', 'text' => 'Vul een zoekwoord in'],
        ['label' => 'searchredirect_searchword', 'text' => 'Zoekwoord'],
        ['label' => 'searchredirect_withlink', 'text' => 'Heeft link'],
        ['label' => 'searchredirect_showall', 'text' => 'Toon alles'],
    ],
    'en' => [
        ['label' => 'searchredirect_all_items', 'text' => 'All search redirects'],
        ['label' => 'searchredirect_add', 'text' => 'Add search redirect'],
        ['label' => 'searchredirect_edit', 'text' => 'Edit search redirect'],
        ['label' => 'searchredirect_no_items', 'text' => 'There are no search redirects to display'],
        ['label' => 'searchredirect_back_overview', 'text' => 'Back to the search redirect overview'],
        ['label' => 'searchredirect_item', 'text' => 'Zoek redirect'],
        ['label' => 'searchredirect_link_page', 'text' => 'Link page'],
        ['label' => 'searchredirect_link_news', 'text' => 'Link news article'],
        ['label' => 'searchredirect_link_photoalbum', 'text' => 'Link photo album'],
        ['label' => 'searchredirect_link_product', 'text' => 'Link catalog product'],
        ['label' => 'searchredirect_item_saved', 'text' => 'Search redirect item has been saved'],
        ['label' => 'searchredirect_item_not_saved', 'text' => 'Search redirect has not been saved, not all fields are (correctly) filled in'],
        ['label' => 'searchredirect_item_deleted', 'text' => 'Search redirect has been deleted'],
        ['label' => 'searchredirect_item_not_deleted', 'text' => 'Search redirect can\'t be deleted'],
        ['label' => 'searchredirect_item_edition', 'text' => 'Edit search redirect'],
        ['label' => 'search_redirect', 'text' => 'Search redirects'],
        ['label' => 'searchredirect_delete', 'text' => 'Delete the search redirect'],
        ['label' => 'searchredirect_no_searchword', 'text' => 'Fill in the search word'],
        ['label' => 'searchredirect_searchword', 'text' => 'Search word'],
        ['label' => 'searchredirect_withlink', 'text' => 'Has a link'],
        ['label' => 'searchredirect_showall', 'text' => 'Show all'],
    ],
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
        ['label' => 'site_read_more', 'text' => 'Lees meer', 'editable' => 1],
        ['label' => 'site_search', 'text' => 'Zoeken', 'editable' => 1],
        ['label' => 'site_search_no_results', 'text' => 'Er zijn geen resultaten gevonden met uw zoekopdracht, verfijn uw zoekopdracht om uw kans te vergroten of neem contact met ons op.', 'editable' => 1],
    ],
];

// check if needed controllers exist
$aLocales = LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]);
if (count($aLocales) > 0) {
    foreach ($aLocales as $oLocale) {
        $aNeededSiteTranslations[$oLocale->getLanguage()->code] = [
            ['label' => 'site_read_more', 'text' => 'Read more', 'editable' => 1],
            ['label' => 'site_search', 'text' => 'Search', 'editable' => 1],
            ['label' => 'site_search_no_results', 'text' => 'No search results found, try searching more specific or contact us.', 'editable' => 1],
        ];
    }
}

// Database checks

if (!moduleExists('pages') || !$oDb->tableExists('pages')) {
    $aLogs[$sModuleName]['errors'][] = 'Page module not installed';
} else {

    if ($oDb->tableExists('pages')) {

        if (!($oPage = PageManager::getPageByName('search', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `search`';
            if ($bInstall) {
                $oPage             = new Page();
                $oPage->languageId = DEFAULT_LANGUAGE_ID;
                $oPage->name       = 'search';
                $oPage->title      = 'Zoeken';
                $oPage->shortTitle = 'Zoeken';
                $oPage->forceUrlPath('/zoeken');
                $oPage->setControllerPath('/modules/search/site/controllers/search.cont.php');
                $oPage->setOnlineChangeable(0);
                $oPage->setDeletable(0);
                $oPage->setMayHaveSub(0);
                $oPage->setLockUrlPath(1);
                $oPage->setLockParent(1);
                $oPage->setHideImageManagement(1);
                $oPage->setHideFileManagement(1);
                $oPage->setHideLinkManagement(1);
                $oPage->setHideVideoLinkManagement(1);
                if ($oPage->isValid()) {
                    PageManager::savePage($oPage);
                } else {
                    _d($oPage->getInvalidProps());
                    die('Can\'t create page `search`');
                }
            }
        }

        foreach (LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]) as $oLocale) {
            if (!($oNewPageNI = PageManager::getPageByName('search', $oLocale->languageId))) {
                $aLogs[$sModuleName]['errors'][] = 'Missing page `search` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
                if ($bInstall) {
                    # create news page
                    $oNewPageNI             = new Page();
                    $oNewPageNI->languageId = $oLocale->languageId;
                    $oNewPageNI->name       = 'search';
                    $oNewPageNI->title      = 'Search';
                    $oNewPageNI->shortTitle = 'Search';
                    $oNewPageNI->forceUrlPath('/search');
                    $oNewPageNI->setControllerPath('/modules/search/site/controllers/search.cont.php');
                    $oNewPageNI->setOnlineChangeable(0);
                    $oNewPageNI->setDeletable(0);
                    $oNewPageNI->setMayHaveSub(0);
                    $oNewPageNI->setLockUrlPath(1);
                    $oNewPageNI->setLockParent(1);
                    $oNewPageNI->setHideImageManagement(1);
                    $oNewPageNI->setHideFileManagement(1);
                    $oNewPageNI->setHideLinkManagement(1);
                    $oNewPageNI->setHideVideoLinkManagement(1);
                    if ($oNewPageNI->isValid()) {
                        PageManager::savePage($oNewPageNI);
                    } else {
                        _d($oNewPageNI->getInvalidProps());
                        die('Can\'t create page `search`');
                    }
                }
            }
        }

        if (!$oDb->tableExists('search_redirects')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing table `search_redirects`';
            if ($bInstall) {
                $sQuery = '
                CREATE TABLE IF NOT EXISTS `search_redirects` (
                  `searchId` int(11) NOT NULL AUTO_INCREMENT,
                  `searchword` varchar(255) NOT NULL,
                  `pageId` int(11) DEFAULT NULL,
                  `newsItemId` int(11) DEFAULT NULL,
                  `photoAlbumId` int(11) DEFAULT NULL,
                  `catalogProductId` int(11) DEFAULT NULL,
                  `hits` INT NOT NULL DEFAULT \'1\',
                  `languageId` INT NOT NULL,
                  PRIMARY KEY (`searchId`),
                  UNIQUE KEY `searchword` (`searchword`),
                  KEY `pageId` (`pageId`),
                  KEY `languageId` (`languageId`),
                  KEY `newsItemId` (`newsItemId`),
                  KEY `photoAlbumId` (`photoAlbumId`),
                  KEY `catalogProductId` (`catalogProductId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
                ';
                $oDb->query($sQuery, QRY_NORESULT);
            }
        }

        ## if newsitems module is installed, check the following
        if ($oDb->tableExists('languages')) {
            // check news items constraint
            if (!$oDb->constraintExists('search_redirects', 'languageId', 'languages', 'languageId')) {
                $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `search_redirects`.`languageId` => `languages`.`languageId`';
                if ($bInstall) {
                    $oDb->addConstraint('search_redirects', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
                }
            }
        }

        ## if newsitems module is installed, check the following
        if ($oDb->tableExists('news_items')) {
            // check news items constraint
            if (!$oDb->constraintExists('search_redirects', 'newsItemId', 'news_items', 'newsItemId')) {
                $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `search_redirects`.`newsItemId` => `news_items`.`newsItemId`';
                if ($bInstall) {
                    $oDb->addConstraint('search_redirects', 'newsItemId', 'news_items', 'newsItemId', 'SET NULL', 'CASCADE');
                }
            }
        }

        ## if photoalbum module is installed, check the following
        if ($oDb->tableExists('photo_albums')) {
            // check news items constraint
            if (!$oDb->constraintExists('search_redirects', 'photoAlbumId', 'photo_albums', 'photoAlbumId')) {
                $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `search_redirects`.`photoAlbumId` => `photo_albums`.`photoAlbumId`';
                if ($bInstall) {
                    $oDb->addConstraint('search_redirects', 'photoAlbumId', 'photo_albums', 'photoAlbumId', 'SET NULL', 'CASCADE');
                }
            }
        }

        ## if page module is installed, check the following
        if ($oDb->tableExists('pages')) {
            // check news items constraint
            if (!$oDb->constraintExists('search_redirects', 'pageId', 'pages', 'pageId')) {
                $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `search_redirects`.`pageId` => `pages`.`pageId`';
                if ($bInstall) {
                    $oDb->addConstraint('search_redirects', 'pageId', 'pages', 'pageId', 'SET NULL', 'CASCADE');
                }
            }
        }

        ## if catalog module is installed, check the following
        if ($oDb->tableExists('catalog_products')) {
            // check news items constraint
            if (!$oDb->constraintExists('search_redirects', 'catalogProductId', 'catalog_products', 'catalogProductId')) {
                $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `search_redirects`.`catalogProductId` => `catalog_products`.`catalogProductId`';
                if ($bInstall) {
                    $oDb->addConstraint('search_redirects', 'catalogProductId', 'catalog_products', 'catalogProductId', 'SET NULL', 'CASCADE');
                }
            }
        }
    } else {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `pages`';
    }
}

