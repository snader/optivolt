<?php

/*
 * Controller to handle normal content pages
 */

# Make pageLayout Object
$oPageLayout = new PageLayout();

# Get Page by url path
$oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

# Check if Page exists or is online
if (empty($oPage) || !$oPage->online) {
    showHttpError('404');
}

# Get submenu structure
if ($oPage->level > 1) {
    $oPageForMenu = PageManager::getPageByUrlPath('/' . http_get('controller'));
} else {
    $oPageForMenu = $oPage;
}

# Get SEO parts
$oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
$oPageLayout->sMetaDescription = $oPage->getMetaDescription();
$oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
$oPageLayout->bIndexable       = $oPage->isIndexable();

# Get OG settings
$oPageLayout->sOGType        = 'website';
$oPageLayout->sOGTitle       = $oPage->getWindowTitle();
$oPageLayout->sOGDescription = $oPage->getMetaDescription();
$oPageLayout->sOGUrl         = getCurrentUrl();
if (($oImage = $oPage->getImages('first-online')) && ($oImageFile = $oImage->getImageFileByReference('crop_small'))) {
    $oPageLayout->sOGImage       = CLIENT_HTTP_URL . $oImageFile->link;
    $oPageLayout->sOGImageWidth  = $oImageFile->getWidth();
    $oPageLayout->sOGImageHeight = $oImageFile->getHeight();
}

# Get crumbles
$oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());

# Get ViewPath
$oPageLayout->sViewPath = getSiteView('search', 'search');

# Get standard media files
$aImages        = $oPage->getImages();
$aVideos = $oPage->getVideoLinks();
$aFiles         = $oPage->getFiles();
$aLinks         = $oPage->getLinks();

## Set variables
$iPageCount = 0;
$iCurrPage  = 0;

if (http_session('search_word') || http_get('q')) {

    ## Search magic, if you so wish.. use GET and or POST
    $sSearchWord             = _e(http_get('q', http_session('search_word')));
    $_SESSION['search_word'] = $sSearchWord;

    ###########################################################################################
    ## Handle predefined searchwords -> redirects
    ###########################################################################################
    $oSearchRedirect = SearchRedirectManager::getSearchRedirectBySearchWord($sSearchWord);
    if ($oSearchRedirect && $oSearchRedirect->getLinkLocation()) {
        $oSearchRedirect->hits = $oSearchRedirect->hits + 1;
        SearchRedirectManager::saveSearchRedirect($oSearchRedirect);

        unset($_SESSION['search_word']);
        http_redirect($oSearchRedirect->getLinkLocation());
    } elseif ($oSearchRedirect) {
        $oSearchRedirect->hits = $oSearchRedirect->hits + 1;
        SearchRedirectManager::saveSearchRedirect($oSearchRedirect);
    } else {
        $oSearchRedirect             = new SearchRedirect();
        $oSearchRedirect->languageId = Locales::language();
        $oSearchRedirect->searchword = $sSearchWord;
        if ($oSearchRedirect->isValid()) {
            SearchRedirectManager::saveSearchRedirect($oSearchRedirect);
        }
    }

    $aResults = [];

    ###########################################################################################
    ## SEARCH PAGES
    ###########################################################################################
    if (moduleExists('pages')) {
        ##Pages
        $oSearch        = new Search();
        $oSearch->table = 'pages'; ## Table to search
        ## NUMMERS VERVANGEN VOOR ARRAY!! -> prefix toevoegen, if leeg, dan `s`
        $oSearch->columns    = ['title' => [10], 'windowTitle' => [5], 'shortTitle' => [5], 'content' => [20]]; ## DB columns + score to sort results properly
        $oSearch->searchword = $sSearchWord;
        $oSearch->class      = 'Page'; ## Class name to place object in

        ## Conditions, build array with conditions. format: column => array(Type of value => value).
        ## Date from and date to functions have also been added.
        $oSearch->aQueryConditions = [
            'online'     => [Search::search_integer => 1],
            'indexable'  => [Search::search_integer => 1],
            'languageId' => [Search::search_integer => Locales::language()],
        ];

        if ($oSearch->isValid()) {
            $aResults = array_merge($aResults, $oSearch->searchObjects());
        }
    }

    ###########################################################################################
    ## SEARCH AGENDA
    ###########################################################################################
    if (moduleExists('agendaItems')) {
        ##Pages
        $oSearch             = new Search();
        $oSearch->table      = 'agenda_items'; ## Table to search
        $oSearch->columns    = ['title' => [10], 'windowTitle' => [5], 'intro' => [15], 'content' => [20]]; ## DB columns + score to sort results properly
        $oSearch->searchword = $sSearchWord;
        $oSearch->class      = 'AgendaItem'; ## Class name to place object in
        #
        ## Conditions, build array with conditions. format: column => array(Type of value => value).
        ## Date from and date to functions have also been added.
        $oSearch->aQueryConditions = [
            'online'     => [Search::search_integer => 1],
            'dateFrom'   => [Search::search_datefromcheck => 1],
            'dateTo'     => [Search::search_datetocheck => 1],
            'languageId' => [Search::search_integer => Locales::language()],
        ];

        if ($oSearch->isValid()) {
            $aResults = array_merge($aResults, $oSearch->searchObjects());
        }
    }

    ###########################################################################################
    ## SEARCH NEWS
    ###########################################################################################
    if (moduleExists('newsItems')) {
        ##Pages
        $oSearch             = new Search();
        $oSearch->table      = 'news_items'; ## Table to search
        $oSearch->columns    = ['title' => [10], 'windowTitle' => [5], 'intro' => [15], 'content' => [20]]; ## DB columns + score to sort results properly
        $oSearch->searchword = $sSearchWord;
        $oSearch->class      = 'NewsItem'; ## Class name to place object in
        #
        ## Conditions, build array with conditions. format: column => array(Type of value => value).
        ## Date from and date to functions have also been added.
        $oSearch->aQueryConditions = [
            'online'     => [Search::search_integer => 1],
            'onlineFrom' => [Search::search_datefromcheck => 1],
            'onlineTo'   => [Search::search_datetocheck => 1],
            'languageId' => [Search::search_integer => Locales::language()],
        ];

        if ($oSearch->isValid()) {
            $aResults = array_merge($aResults, $oSearch->searchObjects());
        }
    }

    ###########################################################################################
    ## SEARCH PHOTOALBUM
    ###########################################################################################
    if (moduleExists('photoAlbums')) {
        ##Pages
        $oSearch             = new Search();
        $oSearch->table      = 'photo_albums'; ## Table to search
        $oSearch->columns    = ['title' => [10], 'windowTitle' => [5], 'shortTitle' => [10], 'content' => [20]]; ## DB columns + score to sort results properly
        $oSearch->searchword = $sSearchWord;
        $oSearch->class      = 'PhotoAlbum'; ## Class name to place object in

        ## Conditions, build array with conditions. format: column => array(Type of value => value).
        ## Date from and date to functions have also been added.
        $oSearch->aQueryConditions = [
            'online'     => [Search::search_integer => 1],
            'languageId' => [Search::search_integer => Locales::language()],
        ];

        if ($oSearch->isValid()) {
            $aResults = array_merge($aResults, $oSearch->searchObjects());
        }
    }

    ###########################################################################################
    ## SEARCH CATALOG
    ###########################################################################################
    if (moduleExists('catalog')) {
        ##Pages
        $oSearch             = new Search();
        $oSearch->table      = 'catalog_products'; ## Table to search
        $oSearch->columns    = ['name' => [10, 'cpTRANS'], 'windowTitle' => [5, 'cpTRANS'], 'description' => [20, 'cpTRANS']]; ## DB columns + score to sort results properly
        $oSearch->searchword = $sSearchWord;
        $oSearch->class      = 'CatalogProduct'; ## Class name to place object in

        ## Set query parts for catalog products:
        CatalogProductManager::setProductFilterSQLPartsByFilter($sFrom, $sWhere, ['languageId' => Locales::Language()], 's');

        $sFrom .= ' JOIN
                        `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `s`.`catalogProductId`';

        ## Special Joins to attach to other important tables
        $oSearch->sJoins = $sFrom;

        ## Extra from value
        $oSearch->sFrom = ',`cpTRANS`.*';

        ## Special conditions for the attached tables
        $oSearch->sManualConditions = $sWhere;

        ## Group by to get unique results
        $oSearch->sGroupBy = 'GROUP BY `s`.catalogProductId';

        if ($oSearch->isValid()) {
            $aResults = array_merge($aResults, $oSearch->searchObjects());
        }
    }

    ###########################################################################################
    ## SEARCH EMPLOYEE
    ###########################################################################################
    if (moduleExists('employees')) {
        ##Pages
        $oSearch             = new Search();
        $oSearch->table      = 'employees'; ## Table to search
        $oSearch->columns    = ['name' => [10], 'windowTitle' => [5], 'jobPosition' => [5], 'intro' => [15], 'content' => [20]]; ## DB columns + score to sort results properly
        $oSearch->searchword = $sSearchWord;
        $oSearch->class      = 'Employee'; ## Class name to place object in
        #
        ## Conditions, build array with conditions. format: column => array(Type of value => value).
        ## Date from and date to functions have also been added.
        $oSearch->aQueryConditions = [
            'online'     => [Search::search_integer => 1],
            'languageId' => [Search::search_integer => Locales::language()],
        ];

        if ($oSearch->isValid()) {
            $aResults = array_merge($aResults, $oSearch->searchObjects());
        }
    }

    ###########################################################################################
    ## SEARCH VACANCIES
    ###########################################################################################
    if (moduleExists('vacancies')) {
        ##Pages
        $oSearch             = new Search();
        $oSearch->table      = 'vacancies'; ## Table to search
        $oSearch->columns    = ['title' => [10], 'windowTitle' => [5], 'function' => [5], 'intro' => [15], 'content' => [20]]; ## DB columns + score to sort results properly
        $oSearch->searchword = $sSearchWord;
        $oSearch->class      = 'Vacancy'; ## Class name to place object in
        #
        ## Conditions, build array with conditions. format: column => array(Type of value => value).
        ## Date from and date to functions have also been added.
        $oSearch->aQueryConditions = [
            'online'     => [Search::search_integer => 1],
            'languageId' => [Search::search_integer => Locales::language()],
        ];

        if ($oSearch->isValid()) {
            $aResults = array_merge($aResults, $oSearch->searchObjects());
        }
    }

    ###########################################################################################
    ## SEARCH FAQ
    ###########################################################################################
    if (moduleExists('faq')) {
        ##Pages
        $oSearch             = new Search();
        $oSearch->table      = 'faq_items'; ## Table to search
        $oSearch->columns    = ['question' => [15], 'answer' => [10]]; ## DB columns + score to sort results properly
        $oSearch->searchword = $sSearchWord;
        $oSearch->class      = 'FAQItem'; ## Class name to place object in
        #
        ## Conditions, build array with conditions. format: column => array(Type of value => value).
        ## Date from and date to functions have also been added.
        $oSearch->aQueryConditions = [
            'online'     => [Search::search_integer => 1],
            'languageId' => [Search::search_integer => Locales::language()],
        ];

        if ($oSearch->isValid()) {
            $aResults = array_merge($aResults, $oSearch->searchObjects());
        }
    }

    ###########################################################################################
    ## Renew sorting by scores
    ###########################################################################################
    usort($aResults, 'compareSortValue');
    $aResults   = array_reverse($aResults, true);
    $iFoundRows = count($aResults);

    ###########################################################################################
    ## Set pagination variables + check them.
    ###########################################################################################
    $iPerPage  = 10;
    $iCurrPage = http_get('page', 1);
    $iStart    = (($iCurrPage - 1) * $iPerPage);
    if (!is_numeric($iCurrPage) || $iCurrPage <= 0) {
        http_redirect($oPage->getUrlPath());
    }

    $iPageCount = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;

    // page is greater than max page count, redirect to main page
    if ($iPageCount > 0 && $iCurrPage > $iPageCount) {
        http_redirect($oPage->getUrlPath());
    }

    // pagecount is greater than 1 and not last page
    if ($iPageCount > 1 && $iCurrPage < $iPageCount) {
        $oPageLayout->sRelNext = CLIENT_HTTP_URL . $oPage->getUrlPath() . '?page=' . ($iCurrPage + 1);
    }

    // pagecount is greater than 1 and not first page
    if ($iPageCount > 1 && $iCurrPage > 1) {
        $oPageLayout->sRelPrev = CLIENT_HTTP_URL . $oPage->getUrlPath();
        // is second page, previous is url without page
        if (($iCurrPage - 1) > 1) {
            $oPageLayout->sRelPrev .= '?page=' . ($iCurrPage - 1);
        }
    }

    ###########################################################################################
    ## Slice the result array into pagination pieces!
    ###########################################################################################
    $aSearchResults = array_slice($aResults, $iStart, $iPerPage);

}

# Get data for this controller

# Include the template
include_once getSiteView('layout');
?>