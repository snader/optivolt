<?php

# set xml declaration
$sXML = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

$sSub      = '';
$aSegments = explode('-', Request::getControllerSegment());
if (count($aSegments) > 1) {
    $sSub = array_pop($aSegments);
}

if ($sSub) {

    $oToday = new Date();
    $aUrls  = [];

    foreach (LocaleManager::getLocalesByFilter() as $oLocale) {
        if (moduleExists('newsItems') && $sSub == 'news') {
            if (PageManager::getPageByName('newsitems')->online) {
                $aNewsItems = NewsItemManager::getNewsItemsByFilter(['languageId' => $oLocale->languageId], null, 0, $iFoundRows, ['IFNULL(`ni`.`modified`, `ni`.`created`)' => 'DESC']);
                $oPage      = PageManager::getPageByName('newsItems', $oLocale->languageId);
                foreach ($aNewsItems AS $oNewsItem) {
                    $oUrl       = new stdClass(); // object for sorting on property later
                    $oUrl->url  = getBaseUrl($oLocale) . $oPage->getUrlPath() . '/' . $oNewsItem->newsItemId . '/' . prettyUrlPart($oNewsItem->title);
                    $date       = new DateTime(($oNewsItem->modified === null ? $oNewsItem->created : $oNewsItem->modified));
                    $oUrl->date = $date->format('c');
                    $aUrls[]    = clone $oUrl;
                }
            }
        } elseif (moduleExists('newsItems') && $sSub == 'newscategories') {
            if (PageManager::getPageByName('newsitems')->online) {
                $aCategories = NewsItemCategoryManager::getNewsItemCategoriesByFilter(['languageId' => $oLocale->languageId], null, 0, $iFoundRows, ['IFNULL(`nic`.`modified`, `nic`.`created`)' => 'DESC']);
                $oPage       = PageManager::getPageByName('newsItems', $oLocale->languageId);
                foreach ($aCategories AS $oCategory) {
                    $oUrl       = new stdClass(); // object for sorting on property later
                    $oUrl->url  = getBaseUrl($oLocale) . $oPage->getUrlPath() . '/' . $oCategory->getUrlPart();
                    $date       = new DateTime(($oCategory->modified === null ? $oCategory->created : $oCategory->modified));
                    $oUrl->date = $date->format('c');
                    $aUrls[]    = clone $oUrl;
                }
            }
        } elseif (moduleExists('pages') && $sSub == 'pages') {
            $aPages = PageManager::getPagesByFilter(['languageId' => $oLocale->languageId, 'online' => 1, 'indexable' => 1], null, 0, $iFoundRows, ['IFNULL(`p`.`modified`, `p`.`created`)' => 'DESC']);
            foreach ($aPages AS $oPage) {
                $oUrl       = new stdClass(); // object for sorting on property later
                $oUrl->url  = getBaseUrl($oLocale) . ($oPage->getUrlPath() != '/' ? $oPage->getUrlPath() : '');
                $date       = new DateTime(($oPage->modified === null ? $oPage->created : $oPage->modified));
                $oUrl->date = $date->format('c');
                $aUrls[]    = clone $oUrl;
            }
        } elseif (moduleExists('photoAlbums') && $sSub == 'photoalbums') {
            $aPhotoAlbums = PhotoAlbumManager::getPhotoAlbumsByFilter(['languageId' => $oLocale->languageId], null, 0, $iFoundRows, ['IFNULL(`pa`.`modified`, `pa`.`created`)' => 'DESC']);
            $oPage        = PageManager::getPageByName('photoalbums', $oLocale->languageId);
            foreach ($aPhotoAlbums AS $oPhotoAlbum) {
                $oUrl       = new stdClass(); // object for sorting on property later
                $oUrl->url  = getBaseUrl($oLocale) . $oPage->getUrlPath() . '/' . $oPhotoAlbum->photoAlbumId . '/' . prettyUrlPart(
                        $oPhotoAlbum->title
                    );
                $date       = new DateTime(($oPhotoAlbum->modified === null ? $oPhotoAlbum->created : $oPhotoAlbum->modified));
                $oUrl->date = $date->format('c');
                $aUrls[]    = clone $oUrl;
            }
        } elseif (moduleExists('catalog') && $sSub == 'catalog') {
            $aProducts = CatalogProductManager::getProductsByFilter([], null, 0, $iFoundRows, ['IFNULL(`cp`.`modified`, `cp`.`created`)' => 'DESC']);
            $oPage        = PageManager::getPageByName('products', $oLocale->languageId);
            foreach ($aProducts AS $oProduct) {
                $oTranslation = $oProduct->getTranslations($oLocale->languageId);
                $oUrl         = new stdClass(); // object for sorting on property later
                $oUrl->url    = getBaseUrl($oLocale) . $oPage->getUrlPath() . '/' . $oProduct->catalogProductId . '/' . prettyUrlPart(
                        !empty($oTranslation->getUrlPart()) ? $oTranslation->getUrlPart() : $oTranslation->name
                    );
                $date         = new DateTime(($oProduct->modified === null ? $oProduct->created : $oProduct->modified));
                $oUrl->date   = $date->format('c');
                $aUrls[]      = clone $oUrl;
            }
        } elseif (moduleExists('catalog') && class_exists('CatalogProductCategoryManager') && $sSub == 'catalogcategories') {
            $aCategories = CatalogProductCategoryManager::getProductCategoriesByFilter([], null, 0, $iFoundRows, ['IFNULL(`cpc`.`modified`, `cpc`.`created`)' => 'DESC']);
            $oPage        = PageManager::getPageByName('product_categories', $oLocale->languageId);
            foreach ($aCategories AS $oCategory) {
                $oTranslation = $oCategory->getTranslations($oLocale->languageId);
                $oUrl         = new stdClass(); // object for sorting on property later
                $oUrl->url    = getBaseUrl($oLocale) . $oPage->getUrlPath() . '/' . $oCategory->catalogProductCategoryId . '/' . prettyUrlPart(
                        !empty($oTranslation->getUrlPart()) ? $oTranslation->getUrlPart() : $oTranslation->name
                    );
                $date         = new DateTime(($oCategory->modified === null ? $oCategory->created : $oCategory->modified));
                $oUrl->date   = $date->format('c');
                $aUrls[]      = clone $oUrl;
            }
        } elseif (moduleExists('employees') && $sSub == 'employees') {
            $aEmployees = EmployeeManager::getEmployeesByFilter([], null, 0, $iFoundRows, ['IFNULL(`e`.`modified`, `e`.`created`)' => 'DESC']);
            $oPage      = PageManager::getPageByName('employees');

            /** @var \Employee $oEmployee */
            foreach ($aEmployees AS $oEmployee) {
                $oUrl       = new stdClass(); // object for sorting on property later
                $oUrl->url  =
                    getBaseUrl($oLocale) . $oPage->getUrlPath() . '/' . $oEmployee->employeeId . '/' . prettyUrlPart(!empty($oEmployee->urlPart) ? $oEmployee->urlPart : $oEmployee->name);
                $date       = new DateTime(($oEmployee->modified === null ? $oEmployee->created : $oEmployee->modified));
                $oUrl->date = $date->format('c');
                $aUrls[]    = clone $oUrl;
            }
        } elseif (moduleExists('agendaItems') && $sSub == 'agenda') {
            if (PageManager::getPageByName('agendaitems')->online) {
                $aAgendaItems = AgendaItemManager::getAgendaItemsByFilter(['languageId' => $oLocale->languageId], null, 0, $iFoundRows, ['`ai`.`dateFrom`' => 'DESC']);
                $oPage        = PageManager::getPageByName('agendaitems', $oLocale->languageId);
                foreach ($aAgendaItems AS $oAgendaItem) {
                    $oUrl       = new stdClass(); // object for sorting on property later
                    $oUrl->url  = getBaseUrl($oLocale) . $oPage->getUrlPath() . '/' . $oAgendaItem->agendaItemId . '/' . prettyUrlPart($oAgendaItem->title);
                    $date       = new DateTime(($oAgendaItem->modified === null ? $oAgendaItem->created : $oAgendaItem->modified));
                    $oUrl->date = $date->format('c');
                    $aUrls[]    = clone $oUrl;
                }
            }
        } elseif (moduleExists('caseItems') && $sSub == 'cases') {
            if (PageManager::getPageByName(CaseItem::PAGE_NAME)->online) {
                $aCaseItems = CaseItemManager::getCaseItemsByFilter(['languageId' => $oLocale->languageId], null, 0, $iFoundRows, ['IFNULL(`ci`.`modified`, `ci`.`created`)' => 'DESC']);
                $oPage = PageManager::getPageByName(CaseItem::PAGE_NAME, $oLocale->languageId);
                foreach ($aCaseItems AS $oCaseItem) {
                    $oUrl       = new stdClass(); // object for sorting on property later
                    $oUrl->url  = getBaseUrl($oLocale) . $oPage->getUrlPath() . '/' . $oCaseItem->caseItemId . '/' . prettyUrlPart($oCaseItem->title);
                    $date       = new DateTime(($oCaseItem->modified === null ? $oCaseItem->created : $oCaseItem->modified));
                    $oUrl->date = $date->format('c');
                    $aUrls[]    = clone $oUrl;
                }
            }
        } elseif (moduleExists('caseItems') && $sSub == 'casecategories') {
            if (PageManager::getPageByName(CaseItem::PAGE_NAME)->online) {
                $aCategories = CaseItemCategoryManager::getCaseItemCategoriesByFilter(['languageId' => $oLocale->languageId], null, 0, $iFoundRows, ['IFNULL(`cic`.`modified`, `cic`.`created`)' => 'DESC']);
                $oPage = PageManager::getPageByName(CaseItem::PAGE_NAME, $oLocale->languageId);
                foreach ($aCategories AS $oCategory) {
                    $oUrl       = new stdClass(); // object for sorting on property later
                    $oUrl->url  = getBaseUrl($oLocale) . $oPage->getUrlPath() . '/' . $oCategory->getUrlPart();
                    $date       = new DateTime(($oCategory->modified === null ? $oCategory->created : $oCategory->modified));
                    $oUrl->date = $date->format('c');
                    $aUrls[]    = clone $oUrl;
                }
            }
        }

    }

    $sXML .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach ($aUrls As $oUrl) {

        $iDaysDiff = ceil(
            Date::strToDate($oUrl->date)
                ->daysDiff($oToday)
        );
        if ($iDaysDiff == 1) {
            $fPrio = 1.0;
        } elseif ($iDaysDiff == 2) {
            $fPrio = 0.7;
        } else {
            $fPrio = 0.5;
        }

        $sXML .= '  <url>' . PHP_EOL;
        $sXML .= '      <loc>' . $oUrl->url . '</loc>' . PHP_EOL;
        $sXML .= '      <lastmod>' . $oUrl->date . '</lastmod>' . PHP_EOL;
        $sXML .= '      <priority>' . $fPrio . '</priority>' . PHP_EOL;
        $sXML .= '  </url>' . PHP_EOL;
    }

# end urlset
    $sXML .= '</urlset>';
} else {

    # set sitemapindex
    $sXML .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
    if (moduleExists('pages')) {
        $sXML .= '<sitemap><loc>' . CLIENT_HTTP_URL . '/sitemap-pages.xml.gz</loc></sitemap>' . PHP_EOL;
    }
    if (moduleExists('newsItems') && PageManager::getPageByName('newsitems')->online) {
        $sXML .= '<sitemap><loc>' . CLIENT_HTTP_URL . '/sitemap-news.xml.gz</loc></sitemap>' . PHP_EOL;
        if (class_exists('NewsItemCategoryManager')) {
            $sXML .= '<sitemap><loc>' . CLIENT_HTTP_URL . '/sitemap-newscategories.xml.gz</loc></sitemap>' . PHP_EOL;
        }
    }
    if (moduleExists('photoAlbums')) {
        $sXML .= '<sitemap><loc>' . CLIENT_HTTP_URL . '/sitemap-photoalbums.xml.gz</loc></sitemap>' . PHP_EOL;
    }
    if (moduleExists('employees')) {
        $sXML .= '<sitemap><loc>' . CLIENT_HTTP_URL . '/sitemap-employees.xml.gz</loc></sitemap>' . PHP_EOL;
    }
    if (moduleExists('catalog')) {
        $sXML .= '<sitemap><loc>' . CLIENT_HTTP_URL . '/sitemap-catalog.xml.gz</loc></sitemap>' . PHP_EOL;
        if (class_exists('CatalogProductCategoryManager')) {
            $sXML .= '<sitemap><loc>' . CLIENT_HTTP_URL . '/sitemap-catalogcategories.xml.gz</loc></sitemap>' . PHP_EOL;
        }
    }
    if (moduleExists('caseItems') && PageManager::getPageByName(CaseItem::PAGE_NAME)->online) {
        $sXML .= '<sitemap><loc>' . CLIENT_HTTP_URL . '/sitemap-cases.xml.gz</loc></sitemap>' . PHP_EOL;
        if (class_exists('CaseItemCategoryManager')) {
            $sXML .= '<sitemap><loc>' . CLIENT_HTTP_URL . '/sitemap-casecategories.xml.gz</loc></sitemap>' . PHP_EOL;
        }
    }
    if (moduleExists('agendaItems') && PageManager::getPageByName('agendaitems')->online) {
        $sXML .= '<sitemap><loc>' . CLIENT_HTTP_URL . '/sitemap-agenda.xml.gz</loc></sitemap>' . PHP_EOL;
    }

    # end sitemap index
    $sXML .= '</sitemapindex>';
}

// make gzip or regular XML
switch (Request::getExtension()) {
    case '.xml.gz':
        header('content-type: application/x-gzip');
        break;
    case '.xml':
    default:
        header("Content-type: text/xml");
        break;
}
echo $sXML;
?>
