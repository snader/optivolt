<?php

class SearchRedirect extends Model
{

    public  $searchId;
    public  $searchword;
    public  $pageId;
    public  $newsItemId;
    public  $photoAlbumId;
    public  $catalogProductId;
    public  $hits            = 1;
    public  $languageId;
    private $oCatalogProduct = null;
    private $oPage           = null;
    private $oNewsItem       = null;
    private $oPhotoAlbum     = null;

    /**
     * validate object
     */

    public function validate()
    {
        if (empty($this->searchword)) {
            $this->setPropInvalid('searchword');
        }
        if (empty($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
    }

    /**
     * get the related Page
     *
     * @return Page
     */
    public function getPage()
    {
        if ($this->oPage === null) {
            $this->oPage = PageManager::getPageById($this->pageId);
        }

        return $this->oPage;
    }

    /**
     * get the related NewsItem
     *
     * @return NewsItem
     */
    public function getNewsItem()
    {
        if ($this->oNewsItem === null) {
            $this->oNewsItem = NewsItemManager::getNewsItemById($this->newsItemId);
        }

        return $this->oNewsItem;
    }

    /**
     * get the related PhotoAlbum
     *
     * @return PhotoAlbum
     */
    public function getPhotoAlbum()
    {
        if ($this->oPhotoAlbum === null) {
            $this->oPhotoAlbum = PhotoAlbumManager::getPhotoAlbumById($this->photoAlbumId);
        }

        return $this->oPhotoAlbum;
    }

    /**
     * get the related CatalogProduct
     *
     * @return CatalogProduct
     */
    public function getCatalogProduct()
    {
        if ($this->oCatalogProduct === null) {
            $this->oCatalogProduct = CatalogProductManager::getProductById($this->catalogProductId);
        }

        return $this->oCatalogProduct;
    }

    /**
     * return link location
     *
     * @return mixed
     */
    public function getLinkLocation()
    {
        if ($this->pageId) {
            if ($this->getPage() && $this->getPage()->online) {
                return $this->getPage()
                    ->getBaseUrlPath();
            }
        } elseif ($this->newsItemId) {
            if ($this->getNewsItem() && $this->getNewsItem()
                    ->isOnline()) {
                return $this->getNewsItem()
                    ->getBaseUrlPath();
            }
        } elseif ($this->photoAlbumId) {
            if ($this->getPhotoAlbum() && $this->getPhotoAlbum()->online) {
                return $this->getPhotoAlbum()
                    ->getBaseUrlPath();
            }
        } elseif ($this->catalogProductId) {
            if ($this->getCatalogProduct() && $this->getCatalogProduct()->online && stristr(getCurrentUrl(), '/dashboard/')) {
                return $this->getCatalogProduct()
                    ->getTranslations('auto-admin')
                    ->getBaseUrlPath();
            } elseif ($this->getCatalogProduct() && $this->getCatalogProduct()->online) {
                return $this->getCatalogProduct()
                    ->getTranslations()
                    ->getBaseUrlPath();
            }
        }

        return false;
    }

    /*
     * Get language
     * @return Language
     */
    public function getLanguage()
    {
        return LanguageManager::getLanguageById($this->languageId);
    }

}

?>