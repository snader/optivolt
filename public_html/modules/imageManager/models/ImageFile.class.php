<?php

class ImageFile extends File
{
    const LAZYLOAD_DEFAULT_RESIZE = 50;

    public  $imageId;
    public  $reference;
    public  $orgTitle;
    public  $type   = Media::IMAGE;
    public  $online = 1;
    public  $imageSizeAttr;
    public $isEditable; 


    private $height = null;
    private $width  = null;

    /**
     * Image extensions that may be converted to WebP
     *
     * @var []
     */
    public static $allowedWebPExtensions = [
        'jpg',
        'jpeg',
        'png',
    ];

    private $properties = [];

    public function __set($name, $value) {
        $this->properties[$name] = $value;
    }

    public function __get($name) {
        return $this->properties[$name] ?? null;
    }

    /**
     * ImageFile constructor.
     *
     * @param array $aData
     * @param bool  $bStripTags
     */
    public function __construct($aData = [], $bStripTags = true)
    {
        if (empty($this->link)) { $this->link = ''; }
        if (in_array(pathinfo($this->link, PATHINFO_EXTENSION), static::$allowedWebPExtensions) &&
            !file_exists(DOCUMENT_ROOT . $this->link . '.webp')) {
            $this->createWebPImage();
        }

        parent::__construct($aData, $bStripTags);
        if (file_exists(DOCUMENT_ROOT . $this->getLinkWithoutQueryString()) && !empty($this->mediaId)) {
            $this->link = $this->getLinkWithTimestamp();
        }
    }

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->imageId)) {
            $this->setPropInvalid('imageId');
        }
        if (empty($this->reference)) {
            $this->setPropInvalid('reference');
        }
        parent::validate();
    }

    /**
     * return width of image
     *
     * @return int
     */
    public function getWidth()
    {
        if (!empty($this->imageSizeAttr) && $this->width === null) {
            $this->setWidthHeight();
        }

        return $this->width;
    }

    /**
     * return height of image
     *
     * @return int
     */
    public function getHeight()
    {
        if (!empty($this->imageSizeAttr) && $this->height === null) {
            $this->setWidthHeight();
        }

        return $this->height;
    }

    /**
     * set width and height in object based on imageSizeAttr
     */
    private function setWidthHeight()
    {
        $aMatches = [];
        if (preg_match('#^width="(\d+)" height="(\d+)"$#', $this->imageSizeAttr, $aMatches)) {
            $this->width  = $aMatches[1];
            $this->height = $aMatches[2];
        }
    }

    /**
     * get resize link for autocrop and resize an ImageFile via uploads controller
     *
     * @param int $iWidth
     * @param int $iHeight
     */
    public function getResizeLink($iWidth = null, $iHeight = null)
    {

        $bSameWidth  = false;
        $bSameHeight = false;
        if (!empty($iWidth) && $iWidth == $this->getWidth()) {
            $bSameWidth = true;
        }
        if (!empty($iHeight) && $iHeight == $this->getHeight()) {
            $bSameHeight = true;
        }

        if (!empty($iWidth) && !empty($iHeight)) {
            if (!$bSameWidth || !$bSameHeight) {
                return preg_replace('#/' . $this->reference . '/#', '/' . $this->reference . '/autoresized_w' . $iWidth . 'h' . $iHeight . '/', $this->link);
            }
        } elseif (!empty($iWidth) && $iWidth != $this->getWidth()) {
            if (!$bSameWidth) {
                return preg_replace('#/' . $this->reference . '/#', '/' . $this->reference . '/autoresized_w' . $iWidth . '/', $this->link);
            }
        } elseif (!empty($iHeight) && ($iHeight != $this->getHeight())) {
            if (!$bSameHeight) {
                return preg_replace('#/' . $this->reference . '/#', '/' . $this->reference . '/autoresized_h' . $iHeight . '/', $this->link);
            }
        }

        return $this->link;
    }

    /**
     * delete all resized versions of this ImageFile
     */
    public function deleteAutoResized()
    {

        // get folder of ImageFiles
        $sImageFileFolder = dirname($this->link);

        // loop trough folder to find `autoresized` folders and check for this file
        if (!$rDir = @opendir(DOCUMENT_ROOT . $sImageFileFolder)) {
            return false;
        }

        while (false !== ($sFile = @readdir($rDir))) {
            # not a file name or not a autoresized folder, continue
            if ($sFile == '.' || $sFile == '..' || !is_dir(DOCUMENT_ROOT . '/' . $sImageFileFolder . '/' . $sFile) || !preg_match('#^(autoresized_(?:w(\d+))?(?:h(\d+))?)$#', $sFile)) {
                continue;
            }

            // loop trough folder to find auto resized image files folders and check for this file
            if (!$rAutoresizedDir = @opendir(DOCUMENT_ROOT . '/' . $sImageFileFolder . '/' . $sFile)) {
                return false;
            }
            while (false !== ($sResizedFile = @readdir($rAutoresizedDir))) {
                # not a file name or not a autoresized folder, continue
                if ($sResizedFile == '.' || $sResizedFile == '..' || $sResizedFile != $this->name) {
                    continue;
                }

                // unlink autoresized image
                @unlink(DOCUMENT_ROOT . '/' . $sImageFileFolder . '/' . $sFile . '/' . $sResizedFile);
            }

            # close dir
            @closedir($rAutoresizedDir);
        }

        # close dir
        @closedir($rDir);
    }

    /**
     * Get link with the timestamp (if it exists)
     *
     * @return string
     */
    public function getLinkWithTimestamp()
    {
        if (!empty($this->modified)) {
            return $this->link .= '?modified=' . strtotime($this->modified);
        }

        return $this->link;
    }

    /**
     * Convert image to WebP
     */
    private function createWebPImage()
    {
        /*$sFile = file_get_contents(CLIENT_HTTP_URL . $this->link);

        if ($sFile) {
            $aImages[$this->link] = base64_encode(file_get_contents(CLIENT_HTTP_URL . $this->link));
        }
        $aWebpFiles = array(); //ImageEditorWebserviceManager::getWebPImages($aImages, []);

        if ($aWebpFiles) {
            foreach ($aWebpFiles['images'] as $sFilePath => $sEncodedFile) {
                if (file_exists(DOCUMENT_ROOT . $sFilePath . '.webp')) {
                    continue;
                }
                file_put_contents(DOCUMENT_ROOT . $sFilePath . '.webp', base64_decode($sEncodedFile));
            }
        }
        */
    }

    /**
     * Get the image attributes for this imageFile
     *
     * @param bool $bBackground is image a normal image, or background image
     * @param int  $iResizeWidth
     *
     * @return string
     */
    public function getImageAttr($bBackground = false, $iResizeWidth = self::LAZYLOAD_DEFAULT_RESIZE)
    {
        return sprintf(
            $bBackground ? 'style="background-image: url(\'%1$s\')" data-bg="%2$s"' : 'src="%1$s" data-src="%2$s"',
            $this->getResizeLink($iResizeWidth),
            $this->link
        );
    }
}

?>