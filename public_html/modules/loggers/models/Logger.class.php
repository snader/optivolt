<?php

class Logger extends Model
{

    public  $loggerId;

    public  $name;
    public  $online          = 1;
    public  $order           = 99999;
    public  $availableFrom;
    public  $deleted         = 0;
    public  $created;
    public  $modified;

    /**
     * validate object
     */

    public function validate()
    {

        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }
        if (!is_numeric($this->order)) {
            $this->setPropInvalid('order');
        }
    }

    /**
     * check if item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {
        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
            if (!$this->isDeleted()) {
                return true;
            }
        }
         return false;
    }

    /**
     * check if item is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
            if (!$this->isDeleted()) {
                return true;
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


    public function isOnlineChangeable()
    {
        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
            if (!$this->isDeleted()) {
                return true;
            }
        }
        return false;
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
            if (!($this->online) || $this->isDeleted()) {
                $bOnline = false;
            }
        }


        return $bOnline;
    }



    /**
     *
     */
    public function getAccountManagers()
    {

        if (!empty($this->planningId)) {
            return PlanningManager::getAccountmanagersByPlanningId($this->planningId);
        }


        return [];
    }


    /**
     * is parent
     */
    public function isParent()
    {
        $aChilds = PlanningManager::getChildsByParentId($this->planningId);
        return count($aChilds);
    }

}