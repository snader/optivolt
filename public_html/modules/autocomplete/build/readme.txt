##################################################
Title: Autocomplete module
Version: v.1.00
Author: Robin van Blaricum
Release date: dd-mm-yyyy
Description: This module allows a more generic way to enable autocomplete fields in the admin environment.
             These could be used for a quick and user friendly way to search and connect content.
##################################################

Below you will find an example implementation.
You need to create an instance of the AutocompleteManager class, which hold fields you can set for usage. For example;

$aAutocompleters           = [];
$aAutocompletersToRegister = [];
    if (moduleExists('newsItems')) {
        $aAutocompletersToRegister[] = [
            'title'       => sysTranslations::get('autocomplete_news_items_title'),
            'masterModel' => Page::class,
            'masterId'    => $oPage->pageId,
            'slaveModel'  => NewsItem::class,
        ];
    }
    foreach ($aAutocompletersToRegister as $aSetting) {
        array_push($aAutocompleters, AutocompleteManager::create($aSetting));
    }

============================================================================================
|Documentation                                                                             |
============================================================================================
|The title will be shown above the field in the form.                                      |
|The masterModel represents the model of the master.                                       |
|The masterId represents the id of the instance for the master model.                      |
|The slaveModel represents the model of the slave.                                         |
|                                                                                          |
|You need to add an entry in the array, which will be created as an AutoComplete instance. |
============================================================================================

============================================================================================
|Requirements                                                                              |
============================================================================================
| - a saveRelation(); method in the master manager                                         |
| - a deleteRelation(); method in the master manager                                       |
| - a getRelations(); method in the master model                                           |
| - a master/slave pivot (use the PivotHelper)                                             |
| - a filter in the byFilter method in the slave using the masterId                        |
| - a filter in the byFilter method in the slave not using the masterId                    |
| - a filter in the byFilter method in the slave for searching items (q)                   |
============================================================================================

##################################################
Changelog:
--------------------------------------------------
Date: dd-mm-yyyy
Version: v.1.00
Description: First release
--------------------------------------------------
##################################################

##################################################
Must have:
--------------------------------------------------

--------------------------------------------------
##################################################

##################################################
Nice to have:
--------------------------------------------------
- pagination
- drag drop order
- click to child edit page (format: /admin/[module]/bewerken/%1$s/xxx)
- easy extendable filter (overrule default language filter f.e.)
- 7.1 > features f.e. scalar types
--------------------------------------------------
##################################################

SAVE RELATION METHOD

    public static function savePageNewsItemRelation(int $iPageId, int $iNewsItemId)
    : void
    {
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


DELETE RELATION METHOD

    public static function deletePageNewsItemRelation(int $iPageId, int $iNewsItemId)
    : void
    {
        $sQuery = 'DELETE FROM `pages_news_items` WHERE `pageId` = ' . db_int($iPageId) . ' AND `newsItemId` = ' . db_int($iNewsItemId);
        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }



GET RELATIONS METHOD

    public function getNewsItems(string $sList = 'online')
    : array
    {
        if (!isset($this->aNewsItems[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aNewsItems[$sList] = NewsItemManager::getNewsItemsByFilter(['pageId' => $this->pageId]);
                    break;
                case 'all':
                    $this->aNewsItems[$sList] = NewsItemManager::getNewsItemsByFilter([
                                                                                          'pageId'  => $this->pageId,
                                                                                          'showAll' => true,
                                                                                      ]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }
        return $this->aNewsItems[$sList];
    }


FILTERS

    if (!empty($aFilter['pageId'])) {
        $sFrom  .= 'JOIN `pages_news_items` AS `pni` ON `pni`.`newsItemId` = `ni`.`newsItemId`
                ';
        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`pni`.`pageId` = ' . db_int($aFilter['pageId']);
    }

    if (!empty($aFilter['NOTpageId'])) {
        $sFrom  .= 'LEFT OUTER JOIN `pages_news_items` AS `pni` ON `pni`.`newsItemId` = `ni`.`newsItemId` AND `pni`.`pageId` = ' . db_int($aFilter['NOTpageId']);
        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`pni`.`pageId` IS NULL)';
    }

    if (!empty($aFilter['q'])) {
        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`ni`.`title` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `ni`.`intro` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `ni`.`content` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ')';
    }


PIVOT FOR INSTALL

    $aErrors = InstallHelper::pivot('pages', 'pageId', 'news_items', 'newsItemId', $bInstall);
    if (count($aErrors)) {
        if (!isset($aLogs[$sModuleName], $aLogs[$sModuleName]['errors'])) {
            $aLogs[$sModuleName]['errors'] = [];
        }

        $aLogs[$sModuleName]['errors'] = array_merge($aLogs[$sModuleName]['errors'], $aErrors);
    }
    unset($aErrors);
