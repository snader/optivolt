<?php

set_time_limit(0);

// set docuemnt root
if (!defined('DOCUMENT_ROOT')) {
    define("DOCUMENT_ROOT", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
}
include_once DOCUMENT_ROOT . '/config/config.inc.php';

$sReferenceName = 'advancedSitemap';

// check if cron lock is set
if (CronManager::isLocked($sReferenceName)) {
    if (DEBUG) {
        echo 'Cron locked `' . $sReferenceName . '`';
    }
    die;
}

// check if cron lock can be set
if (!CronManager::setCronLock($sReferenceName)) {
    if (DEBUG) {
        echo 'Kon cron lock niet setten `' . $sReferenceName . '`';
    } else {
        Debug::logError('0', 'CRON ERROR', __FILE__, __LINE__, 'Kon cron lock niet setten `' . $sReferenceName . '`', Debug::LOG_IN_EMAIL);
    }
    die;
}

// log cron starting
CronManager::log('cron started', $sReferenceName . '.log');

if (moduleExists('pages')) {
    $aPages = PageManager::getPagesByFilter(['lastHourOnly' => true], null, 0, $iFoundRows, ['IFNULL(`p`.`modified`, `p`.`created`)' => 'DESC']);
    if (!empty($aPages)) {
        pingSitemap('pages');
    }
}

if (moduleExists('newsItems')) {
    $aNewsItems = NewsItemManager::getNewsItemsByFilter(['lastHourOnly' => true], null, 0, $iFoundRows, ['IFNULL(`ni`.`modified`, `ni`.`created`)' => 'DESC']);
    if (!empty($aNewsItems)) {
        pingSitemap('news');
    }
    $aCategories = NewsItemCategoryManager::getNewsItemCategoriesByFilter(['lastHourOnly' => true], null, 0, $iFoundRows, ['IFNULL(`nic`.`modified`, `nic`.`created`)' => 'DESC']);
    if (!empty($aCategories)) {
        pingSitemap('newscategories');
    }
}

if (moduleExists('photoAlbums')) {
    $aPhotoAlbums = PhotoAlbumManager::getPhotoAlbumsByFilter(['lastHourOnly' => true], null, 0, $iFoundRows, ['IFNULL(`pa`.`modified`, `pa`.`created`)' => 'DESC']);
    if (!empty($aPhotoAlbums)) {
        pingSitemap('photoalbums');
    }
}
if (moduleExists('catalog')) {
    $aProducts = CatalogProductManager::getProductsByFilter(['lastHourOnly' => true], null, 0, $iFoundRows, ['IFNULL(`cp`.`modified`, `cp`.`created`)' => 'DESC']);
    if (!empty($aProducts)) {
        pingSitemap('catalog');
    }
    $aCategories = CatalogProductCategoryManager::getProductCategoriesByFilter(['lastHourOnly' => true], null, 0, $iFoundRows, ['IFNULL(`cpc`.`modified`, `cpc`.`created`)' => 'DESC']);
    if (!empty($aCategories)) {
        pingSitemap('catalogcategories');
    }
}
if (moduleExists('agendaItems')){
    $aAgendaItems = AgendaItemManager::getAgendaItemsByFilter(['lastHourOnly' => true], null, 0, $iFoundRows, ['IFNULL(`ai`.`modified`, `ai`.`created`)' => 'DESC']);
    if (!empty($aAgendaItems)) {
        pingSitemap('agendaItems');
    }
}
if (moduleExists('caseItems')) {
    $aCaseItems = CaseItemManager::getCaseItemsByFilter(['lastHourOnly' => true], null, 0, $iFoundRows, ['IFNULL(`ci`.`modified`, `ci`.`created`)' => 'DESC']);
    if (!empty($aCaseItems)) {
        pingSitemap('cases');
    }
    $aCaseCategories = CaseItemCategoryManager::getCaseItemCategoriesByFilter(['lastHourOnly' => true], null, 0, $iFoundRows, ['IFNULL(`cic`.`modified`, `cic`.`created`)' => 'DESC']);
    if (!empty($aCaseCategories)) {
        pingSitemap('casecategories');
    }
}
/**
 * do request to google url to `ping` the specific sitemap and log results
 *
 * @global string $sReferenceName
 *
 * @param type    $sModuleName
 */
function pingSitemap($sModuleName)
{
    if (ENVIRONMENT != 'production') {
        return false;
    }
    global $sReferenceName;
    $ch = curl_init('http://www.google.com/webmasters/sitemaps/ping?sitemap=' . CLIENT_HTTP_URL . '/sitemap-' . $sModuleName . '.xml.gz');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $sResponse = curl_exec($ch);

    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200) {
        if (DEBUG) {
            echo 'pinged `' . $sModuleName . '`<br />';
        }
        CronManager::log('pinged `' . $sModuleName . '`', $sReferenceName . '.log');
    } else {
        if (DEBUG) {
            echo 'ping error `' . $sModuleName . '`<br />';
            echo $sResponse;
        } else {
            Debug::logError('0', 'Kon module `' . $sModuleName . '` niet pingen voor `sitemap uitgebreid`', __FILE__, __LINE__, $sResponse, Debug::LOG_IN_EMAIL);
        }
        CronManager::log('ping error `' . $sModuleName . '`', $sReferenceName . '.log');
    }
}

// check if cron can be unlocked
if (!CronManager::unsetCronLock($sReferenceName)) {
    if (DEBUG) {
        echo 'Kon cron lock niet verwijderen `' . $sReferenceName . '`';
    } else {
        Debug::logError('0', 'CRON ERROR', __FILE__, __LINE__, 'Kon cron lock niet verwijderen `' . $sReferenceName . '`', Debug::LOG_IN_EMAIL);
    }
    die;
}

// log cron ending
CronManager::log('cron ended', $sReferenceName . '.log');
?>