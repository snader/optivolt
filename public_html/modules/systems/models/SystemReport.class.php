<?php

class SystemReport extends Model
{

    const FILES_PATH = '/uploads/files/systems';
    const IMAGES_PATH = '/uploads/images/systems';

    public  $systemReportId;
    public  $parentId   = null;
    public  $systemId;

    public  $wideInfo   = null;
    public  $columnA    = null;
    public  $faseA      = null;
    public  $faseB      = null;
    public  $faseC      = null;
    public  $faseD      = null;
    public  $faseE      = null;
    public  $faseF      = null;
    public  $notice     = null;
    public  $userId     = null;

    public  $deleted    = 0;

    public  $created;
    public  $modified;

    protected static $oSystem = null;

    private $aFiles = null; //array with different lists of files
    private $aImages = null; //array with different lists of images
    private $aSubSystemReports;

    /**
     * validate object
     */

    public function validate()
    {
        if (!is_numeric($this->systemId)) {
            $this->setPropInvalid('systemId');
        }

    }

    public function getSystem() {

        if (!static::$oSystem) {
            static::$oSystem = SystemManager::getSystemById($this->systemId);
        }

        return static::$oSystem;
    }


    public function getSubSystemReports()
    {
        $this->aSubSystemReports = SystemReportManager::getSubSystemReports($this->systemReportId);
        return $this->aSubSystemReports;
    }


    /**
     * check if item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {

        if ($this->isDeleted()) {
            return false;
        }

        if (UserManager::getCurrentUser()->isEngineer() || UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isClientAdmin()) {
            return true;
        } else {
            // Indien de afspraak nog niet afgerond is
            if ($this->getSystem()) {
                if ($this->created == NULL) {
                    $this->created = date('Y-m-d', time());
                }
                $oCustomer = $this->getSystem()->getLocation()->getCustomer();

                $oAppointment = CustomerManager::getLastAppointment(UserManager::getCurrentUser()->userId, $oCustomer->customerId);

                if ($oAppointment && $oAppointment["finished"] == 0 && substr($this->created, 0, 4) == substr($oAppointment["visitDate"], 0, 4)) {

                    return true;
                }
            }
        }
        return false;
    }

    /**
     * check if item is deleted
     *
     * @return Boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
    }


    /**
     * check if item is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        if (!$this->isDeleted()) {
            return true;
        }
    }

    /**
     * check if item is online (except with preview mode)
     *
     * @param bool $bPreviewMode
     *
     * @return bool
     */
    public function isOnline($bPreviewMode = false)
    {

        $bOnline = true;
        if (!$bPreviewMode) {
            if (!($this->online)) {
                if (!($this->online) || $this->isDeleted()) {
                    $bOnline = false;
                }
            }
        }

        return $bOnline;
    }



    /**
     * get all files by specific list name for a System
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
                    $this->aFiles[$sList] = SystemReportManager::getFilesByFilter($this->systemReportId);
                    break;
                case 'all':
                    $this->aFiles[$sList] = SystemReportManager::getFilesByFilter($this->systemReportId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aFiles[$sList];
    }
    /**
     * get all images by specific list name for a System
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
                    $this->aImages[$sList] = SystemReportManager::getImagesByFilter($this->systemReportId);
                    break;
                case 'first-online':
                    $aImages = SystemReportManager::getImagesByFilter($this->systemReportId, [], 1);
                    if (!empty($aImages)) {
                        $oImage = $aImages[0];
                    } else {
                        $oImage = null;
                    }
                    $this->aImages[$sList] = $oImage;
                    break;
                case 'all':
                    $this->aImages[$sList] = SystemReportManager::getImagesByFilter($this->systemReportId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aImages[$sList];
    }


    public function renameImages($sNewName = '')
    {

        // RENAME IMAGES
        $aImages = $this->getImages();
        foreach ($aImages as $oImage) {
            $aImageFiles = $oImage->getImageFiles();
            foreach ($aImageFiles as $oImageFile) {
                if ($oImageFile->title == $this->columnA) {
                    ImageManager::updateImageFilesTitleByImage($sNewName, $oImageFile->imageId);
                }
            }
        }
    }

}