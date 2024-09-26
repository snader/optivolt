<?php

class Image
{

    public $imageId;
    public $order  = 99999; //order of images
    public $online = 1;
    // association with imageFile class
    private $aImageFiles = null;

    public $coverImage;

    private $properties = [];

    public function __set($name, $value) {
        $this->properties[$name] = $value;
    }

    public function __get($name) {
        return $this->properties[$name] ?? null;
    }

    /**/

    public function getImageFiles()
    {
        if ($this->aImageFiles === null) {
            $this->aImageFiles = ImageManager::getImageFilesByImageId($this->imageId);
        }

        return $this->aImageFiles;
    }

    /**
     * set imageFiles in image object
     *
     * @param array $aImageFiles
     */
    public function setImageFiles(array $aImageFiles)
    {
        $this->aImageFiles = $aImageFiles;
    }

    /**
     * get imagefile by reference name
     *
     * @param string $sReference
     *
     * @return ImageFile
     */
    public function getImageFileByReference($sReference)
    {
        return ImageManager::getImageFileByImageAndReference($this->imageId, $sReference);
    }

    /**
     * check if image online/offline may be changed
     *
     * @return bool
     */
    public function isOnlineChangeable()
    {
        return true;
    }

    /**
     * check if image is cropable
     *
     * @return bool
     */
    public function isCropable()
    {
        return true;
    }

    /**
     * check if image is editable
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * check if image is deletable
     *
     * @return bool
     */
    public function isDeletable()
    {
        return true;
    }

    /**
     * check if the image has the given imageFiles
     *
     * @param array $aImageFileReferences
     *
     * @return boolean
     */
    public function hasImageFiles(array $aImageFileReferences)
    {
        // get all references
        foreach ($aImageFileReferences AS $sReference) {
            if (!$this->getImageFileByReference($sReference)) {
                return false;
            }
        }

        return true;
    }

}

?>