<?php

class PageController extends CoreController implements MaintenanceInterface
{
    /**
     * @var \Page
     */
    protected $oPage;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        #redirect to account page for signin if not signed in
        if (empty(Customer::getCurrent()) || !is_numeric(Customer::getCurrent()->customerId)) {
            http_redirect(getBaseUrl() . '/account');
        }

        $oPageLayout = $this->getRenderEngine()
            ->setTemplate('layout', 'core')
            ->getLayout();

        # Get ViewPath
        $oPageLayout->sViewPath = getSiteView('page_details', 'pages');
    }

    /**
     * @inheritdoc
     *
     * @param string $sRequestURL
     */
    public function index($sRequestURL = null)
    {
        $oPage = $this->oPage = $this->getPage($sRequestURL);

        # Check if Page exists or is online
        if (empty($oPage) || !$oPage->isOnline()) {
            return Router::httpError('404');
        }

        $this->setMeta($oPage);

        # Get submenu structure
        if ($oPage->level > 1) {
            $oPageForMenu = $oPage->getParent();
        } else {
            $oPageForMenu = $oPage;
        }

        $this->getRenderEngine()
            ->setVariables(
                [
                    'oPage'        => $oPage,
                    'oPageForMenu' => $oPageForMenu,
                    'aImages'      => $oPage->getImages(),
                    'aVideos'      => $oPage->getVideoLinks(),
                    'aFiles'       => $oPage->getFiles(),
                    'aLinks'       => $oPage->getLinks(),
                    'aCards'       => moduleExists('cards') ? $oPage->getCards() : null,
                    'oForm'        => moduleExists('forms') ? $oPage->getForm() : null,
                    'aWhitePapers' => moduleExists('whitePapers') ? $oPage->getWhitePapers() : null,
                    'aUsps'        => moduleExists('usps') ? $oPage->getUsps() : null,
                ]
            );
    }

    /**
     * Set the meta data
     *
     * @param \Page $oPage
     */
    protected function setMeta(Page $oPage)
    {
        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        # Get SEO parts
        $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
        $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
        $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
        $oPageLayout->bIndexable       = $oPage->isIndexable();
        $oPageLayout->sCanonical       = $oPageLayout->getCustomCanonical($oPage);

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
    }

    /**
     * Retrieve the page by using RTL lookup
     *
     * @param string $sRequestURL
     *
     * @return \Page
     */
    protected function getPage($sRequestURL = null)
    {
        $sRequestURL = $sRequestURL ?: Request::getPath();
        $aRequestURL = explode('/', $sRequestURL);
        do {
            $oPage = PageManager::getPageByUrlPath(implode('/', $aRequestURL), true, Locales::language());
            array_pop($aRequestURL);
        } while (!$oPage && $aRequestURL);

        return $oPage;
    }

    /**
     * define maintenance handling
     */
    public function checkMaintanance()
    {

        # can I handle maintenance status?
        if (Settings::exists('maintenance')) {

            # in maintenance mode and having maintenance page?
            if (SettingManager::getSettingByName('maintenance')->value == 1 && $oPageMaintenance = PageManager::getPageByName('maintenance')) {

                # save previous called URL
                if (empty(Session::get('referrerMaintenanceUrl'))) {
                    Session::set('referrerMaintenanceUrl', getCurrentUrlPath());
                }

                # when URL is not equal to current, redirect
                if ($oPageMaintenance->getUrlPath() != getCurrentUrlPath()) {
                    Router::redirect($oPageMaintenance->getUrlPath());
                }
            } else {

                # check if first call after maintenance mode
                if ($sReferrer = Session::get('referrerMaintenanceUrl')) {
                    Session::destroy('referrerMaintenanceUrl');
                    # redirect to last called URL
                    Router::redirect($sReferrer);
                }
            }
        }
    }

}
