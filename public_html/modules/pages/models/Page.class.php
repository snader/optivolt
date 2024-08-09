<?php

class Page extends Model
{

    const FILES_PATH = '/uploads/files/page';

    public  $pageId                  = null;
    public  $languageId              = null;
    public  $windowTitle             = null; //browser window title
    public  $metaKeywords            = null; //meta tag keywords
    public  $metaDescription         = null; //meta tag description
    public  $name; // unqiue name for page to use in code, for special handling, instead of ID
    public  $title                   = null; //page title
    public  $intro                   = null;
    public  $content                 = null; //page content
    public  $shortTitle              = null; //short title f.e. for use in menus
    public  $parentPageId            = null; //page parent id, if set then this is a subpage
    public  $online                  = 1; //0 no, 1 yes
    private $urlPath                 = null; // unique part of the url e.g. http://www.website.nl[/url/part].html?test=1
    private $urlPart                 = null; // part of the url to use in stead of short link text
    private $urlParameters           = null; // part of the url to set some parameters
    private $controllerPath          = '/modules/pages/site/controllers/page.cont.php'; //path to the controller to handle this object
    public  $created                 = null; //created timestamp
    public  $modified                = null; //last modified timestamp
    public  $order                   = 99999; //for ordering pages
    public  $level                   = 1; //main pages have level 0
    public  $customCanonical         = null; //custom canonical entry if wanted
    private $onlineChangeable        = 1; //online is changeable 1 by default
    private $editable                = 1; //editable 1 by default
    private $deletable               = 1; //deletable 1 by default
    private $inMenu                  = 1; //inMenu 1 by default
    private $inFooter                = 0; //inFooter 0 by default
    private $indexable               = 1; //indexable 1 by default
    private $includeParentInUrlPath  = 1; //indexable 1 by default
    private $mayHaveSub              = 1; //mayHaveSub 1 by default
    private $lockUrlPath             = 0; //url path does not change with title 0 by default
    private $lockParent              = 0; //do not change parentPageId 0 by default
    private $hideImageManagement     = 0; //do not hide imagemanagement by default
    private $hideFileManagement      = 0; //do not hide filemanagement 0 by default
    private $hideLinkManagement      = 0; //do not hide linkmanagement 0 by default
    private $hideVideoLinkManagement = 0; //do hide videolinkmanagement 0 by default
    private $hideBrandboxManagement  = 1; //do hide hideBrandboxManagement 1 by default
    private $hideFormManagement      = 0; //for not showing linking form to page, when formModule is installed
    public  $formId                  = null; //for linking form to page, when formModule is installed
    private $aSubPages               = null; //array with different lists of sub pages
    private $aImages                 = null; //array with different lists of images
    private $aFiles                  = null; //array with different lists of files
    private $aLinks                  = null; //array with different lists of links
    private $aCards                  = null; //array with different lists of cards
    private $aVideoLinks             = null; //array with different lists of video links
    private $oParent                 = null; //parent page
    private $oLanguage               = null; // language of the page
    private $aLocales                = null; // locales of the page
    private $oForm                   = null; // form of the page
    private $aNewsItems              = [];
    private $aWhitePapers            = [];
    private $aUsps                   = [];

    public $showNews;
    public $showOnHome;

    /**
     * @var \CallToAction[]
     */
    protected $cta;

    /**
     * @var \BrandboxItem[]
     */
    protected $bb;

    /**
     * validate object
     */

    public function validate()
    {
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->controllerPath)) {
            $this->setPropInvalid('controllerPath');
        }
        if (empty($this->title)) {
            $this->setPropInvalid('title');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }
        if (!empty($this->name) && ($oPage = PageManager::getPageByName($this->name, $this->languageId))) {
            if ($oPage->pageId != $this->pageId) {
                $this->setPropInvalid('name');
            }
        }
        if ($this->getLockUrlPath() && $this->urlPath && ($oPage = PageManager::getPageByUrlPath($this->urlPath, false, $this->languageId))) {
            if ($oPage->pageId != $this->pageId) {
                $this->setPropInvalid('urlPath');
            }
        }
    }

    /**
     * return all subpages for this page
     */
    public function getSubPages($sList = 'online-menu')
    {
        if (!isset($this->aSubPages[$sList])) {
            switch ($sList) {
                case 'online-menu':
                    $this->aSubPages[$sList] = PageManager::getPagesByFilter(['parentPageId' => $this->pageId, 'inMenu' => 1]);
                    break;
                case 'online-all':
                    $this->aSubPages[$sList] = PageManager::getPagesByFilter(
                        [
                            'parentPageId' => $this->pageId,
                            'showAll'      => 1,
                            'online'       => 1,
                        ]
                    );
                    break;
                case 'all':
                    $this->aSubPages[$sList] = PageManager::getPagesByFilter(
                        [
                            'parentPageId' => $this->pageId,
                            'showAll'      => 1,
                        ]
                    );
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aSubPages[$sList];
    }

    /**
     * check if page has subs
     */
    public function hasSubPages($sList = 'online-menu')
    {
        return count($this->getSubPages($sList)) > 0;
    }

    /**
     * get Language of this page
     *
     * @return $oLanguage
     */
    public function getLanguage()
    {
        if ($this->oLanguage === null) {
            $this->oLanguage = LanguageManager::getLanguageById($this->languageId);
        }

        return $this->oLanguage;
    }

    /**
     * get Locales of this page
     *
     * @return $aLocales
     */
    public function getLocales()
    {
        if ($this->aLocales === null) {
            $this->aLocales = LocaleManager::getLocalesByFilter(['languageId' => $this->languageId]);
        }

        return $this->aLocales;
    }

    /**
     * get url to page
     *
     * @return string
     */
    public function getUrlPath()
    {
        return $this->urlPath;
    }

    /**
     * get url to page with base url
     *
     * @return string
     */
    public function getBaseUrlPath()
    {
        return getBaseUrl() . $this->urlPath;
    }

    /**
     * return part of the url for this page
     *
     * @return string
     */
    public function getUrlPart()
    {
        return $this->urlPart;
    }

    /**
     * set url parameters
     *
     * @param string $sUrlPart
     */
    public function setUrlParameters($sUrlParameters)
    {
        $this->urlParameters = $sUrlParameters;
    }

    /**
     * return the url parameters
     *
     * @return string
     */
    public function getUrlParameters()
    {
        return $this->urlParameters;
    }

    /**
     * set url part for this page
     *
     * @param string $sUrlPart
     */
    public function setUrlPart($sUrlPart)
    {
        $this->urlPart = prettyUrlPart($sUrlPart);
    }

    /**
     * return modified timestamp in format
     *
     * @param string $sFormat (optional)
     */
    public function getModified($sFormat = '%d-%m-%Y %H:%M')
    {
        return Date::strToDate($this->modified)
            ->format($sFormat);
    }

    /**
     * return controller path
     *
     * @return string
     */
    public function getControllerPath()
    {
        return $this->controllerPath;
    }

    /**
     * get parent
     *
     * @return Page object
     */
    public function getParent()
    {
        if ($this->oParent === null) {
            $this->oParent = PageManager::getPageById($this->parentPageId);
        }

        return $this->oParent;
    }

    /**
     * generate a urlPath for this page
     *
     * @return string generated urlPath
     */
    public function generateUrlPath()
    {
        $sUrlPath = '';

        if ($this->getIncludeParentInUrlPath()) {
            $oParentPage = $this->getParent();
            if ($oParentPage && $oParentPage->getUrlPath() !== '/') {
                $sUrlPath = $oParentPage->getUrlPath();
            }
        }

        return $sUrlPath . '/' . (!empty($this->urlPart) ? $this->urlPart : prettyUrlPart($this->getShortTitle()));
    }

    /**
     * generate urlPath and set in object
     */
    public function setUrlPath()
    {
        $this->urlPath = $this->generateUrlPath();
    }

    /**
     * force urlPath, do your own validation
     */
    public function forceUrlPath($sUrlPath)
    {
        $this->urlPath = $sUrlPath;
    }

    /**
     * calculate level and set in object
     */
    public function setLevel()
    {
        if ($this->parentPageId) {
            $this->level = $this->getParent()->level + 1;
        } else {
            $this->level = 1;
        }
    }

    /**
     * check if page is editable
     *
     * @return boolean
     */
    public function isEditable()
    {
        $oCurrentUser = UserManager::getCurrentUser();

        return $this->editable || ($oCurrentUser && $oCurrentUser->isAdmin()); // admin can always edit pages
    }

    /**
     * check if page is online changeable
     *
     * @return boolean
     */
    public function isOnlineChangeable()
    {
        return $this->onlineChangeable;
    }

    /**
     * check if page is deletable
     *
     * @return boolean
     */
    public function isDeletable()
    {
        return $this->hasSubPages('all') === false && $this->deletable;
    }

    /**
     * check showing page in menu
     *
     * @return boolean
     */
    public function showInMenu()
    {
        return $this->inMenu && $this->online;
    }

    /**
     * check if page is indexable
     *
     * @return boolean
     */
    public function isIndexable()
    {
        return $this->indexable;
    }

    /**
     * check if page can have sub page
     *
     * @return boolean
     */
    public function mayHaveSub()
    {
        return $this->mayHaveSub;
    }

    /**
     * just returns integer, DO NOT USE FOR IS MAY HAVE SUB CHECKING
     *
     * @return boolean
     */
    public function getMayHaveSub()
    {
        return $this->mayHaveSub;
    }

    /**
     * set onlineChangeable
     *
     * @param boolean $bOnlineSchangeable
     */
    public function setOnlineChangeable($bOnlineSchangeable)
    {
        $this->onlineChangeable = $bOnlineSchangeable;
    }

    /**
     * set controllerPath
     *
     * @param string $sControllerPath
     */
    public function setControllerPath($sControllerPath)
    {
        $this->controllerPath = $sControllerPath;
    }

    /**
     * set editable
     *
     * @param boolean $bEditable
     */
    public function setEditable($bEditable)
    {
        $this->editable = $bEditable;
    }

    /**
     * set deletable
     *
     * @param boolean $bDeletable
     */
    public function setDeletable($bDeletable)
    {
        $this->deletable = $bDeletable;
    }

    /**
     * set inMenu
     *
     * @param boolean $bInMenu
     */
    public function setInMenu($bInMenu)
    {
        $this->inMenu = $bInMenu;
    }

    /**
     * set inFooter
     *
     * @param boolean $bInFooter
     */
    public function setInFooter($bInFooter)
    {
        $this->inFooter = $bInFooter;
    }

    /**
     * set indexable
     *
     * @param boolean $bIndexable
     */
    public function setIndexable($bIndexable)
    {
        $this->indexable = $bIndexable;
    }

    /**
     * set indexable
     *
     * @param boolean $bIncludeParentInUrlPath
     */
    public function setIncludeParentInUrlPath($bIncludeParentInUrlPath)
    {
        $this->includeParentInUrlPath = $bIncludeParentInUrlPath;
    }

    /**
     * set mayHavesub
     *
     * @param boolean $bMayHavesub
     */
    public function setMayHaveSub($bMayHavesub)
    {
        $this->mayHaveSub = $bMayHavesub;
    }

    /**
     * set lockUrlPath
     *
     * @param boolean $bLockUrlPath
     */
    public function setLockUrlPath($bLockUrlPath)
    {
        $this->lockUrlPath = $bLockUrlPath;
    }

    /**
     * set lockParent
     *
     * @param boolean $bLockParent
     */
    public function setLockParent($bLockParent)
    {
        $this->lockParent = $bLockParent;
    }

    /**
     * set hideFormManagement
     *
     * @param boolean $bHideFormManagement
     */
    public function setHideFormManagement($bHideFormManagement)
    {
        $this->hideFormManagement = $bHideFormManagement;
    }

    /**
     * set hideImageManagement
     *
     * @param boolean $bHideImageManagement
     */
    public function setHideImageManagement($bHideImageManagement)
    {
        $this->hideImageManagement = $bHideImageManagement;
    }

    /**
     * set hideFileManagement
     *
     * @param boolean $bHideFileManagement
     */
    public function setHideFileManagement($bHideFileManagement)
    {
        $this->hideFileManagement = $bHideFileManagement;
    }

    /**
     * set hideLinkManagement
     *
     * @param boolean $bHideLinkManagement
     */
    public function setHideLinkManagement($bHideLinkManagement)
    {
        $this->hideLinkManagement = $bHideLinkManagement;
    }

    /**
     * set hideVideoLinkManagement
     *
     * @param boolean $bHideVideoLinkManagement
     */
    public function setHideVideoLinkManagement($bHideVideoLinkManagement)
    {
        $this->hideVideoLinkManagement = $bHideVideoLinkManagement;
    }

    /**
     * set hideBrandboxManagement
     *
     * @param boolean $bHideBrandboxManagement
     */
    public function setHideBrandboxManagement($bHideBrandboxManagement)
    {
        $this->hideBrandboxManagement = $bHideBrandboxManagement;
    }

    /**
     * just returns integer, DO NOT USE FOR IS ONLINECHANGEABLE CHECKING
     * return value of onlineChangeable
     *
     * @return int
     */
    public function getOnlineChangeable()
    {
        return $this->onlineChangeable;
    }

    /**
     * just returns integer, DO NOT USE FOR IS EDITABLE CHECKING
     * return value of editable
     *
     * @return int
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * just returns integer, DO NOT USE FOR IS DELETABLE CHECKING
     * return value of deletable
     *
     * @return int
     */
    public function getDeletable()
    {
        return $this->deletable;
    }

    /**
     * just returns integer, DO NOT USE FOR SHOW IN MENU CHECKING
     * return value of inMenu
     *
     * @return int
     */
    public function getInMenu()
    {
        return $this->inMenu;
    }

    /**
     * just returns integer, DO NOT USE FOR SHOW IN FOOTER CHECKING
     * return value of inFooter
     *
     * @return int
     */
    public function getInFooter()
    {
        return $this->inFooter;
    }

    /**
     * just returns integer, DO NOT USE FOR INDEXABLE CHECKING
     * return value of indexable
     *
     * @return int
     */
    public function getIndexable()
    {
        return $this->indexable;
    }

    /**
     * just returns integer, DO NOT USE FOR INDEXABLE CHECKING
     * return value of indexable
     *
     * @return int
     */
    public function getIncludeParentInUrlPath()
    {
        return $this->includeParentInUrlPath;
    }

    /**
     * just returns integer, DO NOT USE FOR LOCK URL PATH CHECKING
     * return value of lockUrlPath
     *
     * @return int
     */
    public function getLockUrlPath()
    {
        return $this->lockUrlPath;
    }

    /**
     * just returns integer, DO NOT USE FOR LOCK PARENT CHECKING
     * return value of lockParent
     *
     * @return int
     */
    public function getLockParent()
    {
        return $this->lockParent;
    }

    /**
     * just returns integer, DO NOT USE FOR HIDE IMAGE MANAGEMENT CHECKING
     * return value of hideImageManagement
     *
     * @return int
     */
    public function getHideImageManagement()
    {
        return $this->hideImageManagement;
    }

    /**
     * just returns integer, DO NOT USE FOR HIDE FILE MANAGEMENT CHECKING
     * return value of hideFileManagement
     *
     * @return int
     */
    public function getHideFileManagement()
    {
        return $this->hideFileManagement;
    }

    /**
     * just returns integer, DO NOT USE FOR HIDE LINK MANAGEMENT CHECKING
     * return value of hideLinkManagement
     *
     * @return int
     */
    public function getHideLinkManagement()
    {
        return $this->hideLinkManagement;
    }

    /**
     * just returns integer, DO NOT USE FOR HIDE LINK MANAGEMENT CHECKING
     * return value of hideFormManagement
     *
     * @return int
     */
    public function getHideFormManagement()
    {
        return $this->hideFormManagement;
    }

    /**
     * just returns integer, DO NOT USE FOR HIDE VIDEO LINK MANAGEMENT CHECKING
     * return value of hideVideoLinkManagement
     *
     * @return int
     */
    public function getHideVideoLinkManagement()
    {
        return $this->hideVideoLinkManagement;
    }

    /**
     * just returns integer, DO NOT USE FOR HIDE BRANDBOX MANAGEMENT CHECKING
     * return value of hideBrandboxManagement
     *
     * @return int
     */
    public function getHideBrandboxManagement()
    {
        return $this->hideBrandboxManagement;
    }

    /**
     * check to hide image management
     *
     * @return boolean
     */
    public function hideImageManagement()
    {
        return $this->hideImageManagement;
    }

    /**
     * check to hide image management
     *
     * @return boolean
     */
    public function hideFormManagement()
    {
        return $this->hideFormManagement;
    }

    /**
     * check to hide file management
     *
     * @return boolean
     */
    public function hideFileManagement()
    {
        return $this->hideFileManagement;
    }

    /**
     * check to hide link management
     *
     * @return boolean
     */
    public function hideLinkManagement()
    {
        return $this->hideLinkManagement;
    }

    /**
     * check to hide video link management
     *
     * @return boolean
     */
    public function hideVideoLinkManagement()
    {
        return $this->hideVideoLinkManagement;
    }

    /**
     * check to hide brandbox management
     *
     * @return boolean
     */
    public function hideBrandboxManagement()
    {
        return $this->hideBrandboxManagement;
    }

    /**
     * check to lock parent
     *
     * @return boolean
     */
    public function lockParent()
    {
        return $this->lockParent;
    }

    /**
     * get all images by specific list name for a page
     *
     * @param string $sList
     *
     * @return Image or array Images
     */
    public function getImages($sList = 'online')
    {
        if (!isset($this->aImages[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aImages[$sList] = PageManager::getImagesByFilter($this->pageId);
                    break;
                case 'first-online':
                    $aImages = PageManager::getImagesByFilter($this->pageId, [], 1);
                    if (!empty($aImages)) {
                        $oImage = $aImages[0];
                    } else {
                        $oImage = null;
                    }
                    $this->aImages[$sList] = $oImage;
                    break;
                case 'all':
                    $this->aImages[$sList] = PageManager::getImagesByFilter($this->pageId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aImages[$sList];
    }

    /**
     * get all files by specific list name for a page
     *
     * @param string $sList
     *
     * @return array File
     */
    public function getFiles($sList = 'online')
    {
        if (!isset($this->aFiles[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aFiles[$sList] = PageManager::getFilesByFilter($this->pageId);
                    break;
                case 'all':
                    $this->aFiles[$sList] = PageManager::getFilesByFilter($this->pageId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aFiles[$sList];
    }

    /**
     * get all links by specific list name for a page
     *
     * @param string $sList
     *
     * @return array Link
     */
    public function getLinks($sList = 'online')
    {
        if (!isset($this->aLinks[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aLinks[$sList] = PageManager::getLinksByFilter($this->pageId);
                    break;
                case 'all':
                    $this->aLinks[$sList] = PageManager::getLinksByFilter($this->pageId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aLinks[$sList];
    }

    /**
     * get all cards by specific card name for a page
     *
     * @param string $aCards
     *
     * @return array Cards
     */
    public function getCards($sList = 'online')
    {
        if (!isset($this->aCards[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aCards[$sList] = CardManager::getCardsByFilter(['pageId' => $this->pageId]);
                    break;
                case 'all':
                    $this->aCards[$sList] = CardManager::getCardsByFilter($this->pageId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aCards[$sList];
    }

    /**
     * get all video links by specific list name for a page
     *
     * @param string $sList
     *
     * @return array VideoLink
     */
    public function getVideoLinks($sList = 'online')
    {
        if (!isset($this->aVideoLinks[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aVideoLinks[$sList] = PageManager::getVideoLinksByFilter($this->pageId);
                    break;
                case 'all':
                    $this->aVideoLinks[$sList] = PageManager::getVideoLinksByFilter($this->pageId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aVideoLinks[$sList];
    }

    /**
     * get form when linked to page
     *
     * @return object form
     */
    public function getForm()
    {
        if (!isset($this->oForm)) {
            $this->oForm = FormManager::getFormById($this->formId);
        }

        return $this->oForm;
    }

    /**
     * return the window title if there is one, otherwise return title
     *
     * @return string
     */
    public function getWindowTitle()
    {
        return $this->windowTitle ? $this->windowTitle : $this->title;
    }

    /**
     * return meta description if exists or a
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription ? $this->metaDescription : generateMetaDescription($this->content);
    }

    /**
     * return meta keywords
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * return short title but fall back on title if short does not exists
     *
     * @return string
     */
    public function getShortTitle()
    {
        return $this->shortTitle ? $this->shortTitle : $this->title;
    }

    /**
     * generates an array of the breadcrumbs, but in reverse order
     *
     * @param array $aCrumbles
     */
    private function generateCrumbles(&$aCrumbles = [])
    {
        $aCrumbles[$this->getShortTitle()] = $this->online ? $this->getBaseUrlPath() : null;

        $oParentPage = $this->getParent();
        if (!empty($oParentPage)) {
            $oParentPage->generateCrumbles($aCrumbles);
        }
    }

    /**
     * get an array of the breadcrumbs in the right order
     *
     * @return array
     */
    public function getCrumbles()
    {
        $this->generateCrumbles($aCrumbles);

        return array_reverse($aCrumbles);
    }

    /**
     * copy specific predefined properties from parent
     *
     * @param Page $oPage
     */
    public function copyFromParent(Page $oPage)
    {
        $this->parentPageId            = $oPage->pageId;
        $this->online                  = $oPage->online;
        $this->onlineChangeable        = $oPage->onlineChangeable;
        $this->editable                = $oPage->editable;
        $this->deletable               = $oPage->deletable;
        $this->inMenu                  = $oPage->inMenu;
        $this->inFooter                = $oPage->inFooter;
        $this->indexable               = $oPage->indexable;
        $this->includeParentInUrlPath  = $oPage->includeParentInUrlPath;
        $this->mayHaveSub              = $oPage->mayHaveSub;
        $this->lockUrlPath             = $oPage->lockUrlPath;
        $this->controllerPath          = $oPage->controllerPath;
        $this->lockParent              = $oPage->lockParent;
        $this->hideImageManagement     = $oPage->hideImageManagement;
        $this->hideFileManagement      = $oPage->hideFileManagement;
        $this->hideLinkManagement      = $oPage->hideLinkManagement;
        $this->hideVideoLinkManagement = $oPage->hideVideoLinkManagement;
        $this->hideBrandboxManagement  = $oPage->hideBrandboxManagement;
        $this->hideFormManagement      = $oPage->hideFormManagement;
    }

    /**
     * @param bool $bOnline
     * @param bool $bForce
     *
     * @return \BrandboxItem[]
     */
    public function getBrandboxItems($bOnline = false, $bForce = false)
    {
        if (!$this->bb || $bForce) {
            $this->bb = PageManager::getBrandboxItemsByFilter($this->pageId, $bOnline ? ['showAll' => true] : []);
        }

        return $this->bb;
    }

    /**
     * @return array
     */
    public function getBrandboxItemIds()
    {
        $aIds = [];
        foreach ($this->getBrandboxItems(true) as $oBB) {
            array_push($aIds, $oBB->brandboxItemId);
        }

        return $aIds;
    }

    /**
     * Get the online status of a page, this also checks for any parent page(s)
     *
     * @return bool
     */
    public function isOnline()
    {
        if ($this->online) {
            if ($oParent = $this->getParent()) {
                return $oParent->isOnline();
            }

            return true;
        }

        return false;
    }

    /**
     * Obtain all NewsItem this Page is linked to
     *
     * @param string $sList
     *
     * @return array NewsItem
     */
    public function getNewsItems(string $sList = 'online'): array
    {
        if (moduleExists('newsItems')) {
            if (!isset($this->aNewsItems[$sList])) {
                switch ($sList) {
                    case 'online':
                        $this->aNewsItems[$sList] = NewsItemManager::getNewsItemsByFilter(['pageId' => $this->pageId]);
                        break;
                    case 'all':
                        $this->aNewsItems[$sList] = NewsItemManager::getNewsItemsByFilter(
                            [
                                'pageId'  => $this->pageId,
                                'showAll' => true,
                            ]
                        );
                        break;
                    default:
                        die('no option');
                        break;
                }
            }

            return $this->aNewsItems[$sList];
        }

        return [];
    }

    /**
     * Get all connected whitepapers
     *
     * @param string $sList
     *
     * @return array
     */
    public function getWhitePapers(string $sList = 'online'): array
    {
        if (!isset($this->aWhitePapers[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aWhitePapers[$sList] = WhitePaperManager::getWhitePapersByFilter(['whitePaperPageId' => $this->pageId]);
                    break;
                case 'all':
                    $this->aWhitePapers[$sList] = WhitePaperManager::getWhitePapersByFilter(
                        [
                            'whitePaperPageId'  => $this->pageId,
                            'showAll' => true,
                        ]
                    );
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aWhitePapers[$sList];
    }

    /**
     * Obtain all linked usps
     *
     * @param string $sList
     *
     * @return array
     */
    public function getUsps(string $sList = 'online'): array
    {
        if (!isset($this->aUsps[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aUsps[$sList] = UspManager::getUspsByFilter(['pageId' => $this->pageId]);
                    break;
                case 'all':
                    $this->aUsps[$sList] = UspManager::getUspsByFilter(
                        [
                            'pageId'  => $this->pageId,
                            'showAll' => true,
                        ]
                    );
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aUsps[$sList];
    }
}
