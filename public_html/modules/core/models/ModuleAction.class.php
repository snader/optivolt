<?php

/* Models and managers used by the ModuleAction model */
require_once 'Model.class.php';

class ModuleAction extends Model
{

    public  $moduleActionId = null;
    public  $moduleId;
    public  $displayName; //name to show in overviews
    public  $name; // name to use for checking in code
    public  $created; // created timestamp
    public  $createdBy; // created by
    public  $modified; // last modified timestamp
    public  $modifiedBy; // last modified by
    private $editable       = 1; //editable 1 by default
    private $deletable      = 0; //deletable 1 by default
    private $oModule        = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->moduleId)) {
            $this->setPropInvalid('moduleId');
        }
        if (empty($this->displayName)) {
            $this->setPropInvalid('displayName');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
        if (!is_numeric($this->editable)) {
            $this->setPropInvalid('editable');
        }
        if (!is_numeric($this->deletable)) {
            $this->setPropInvalid('deletable');
        }
    }

    /**
     * checl if is editable
     *
     * @global User $oCurrentUser
     * @return boolean
     */
    public function isEditable()
    {
        $oCurrentUser = UserManager::getCurrentUser();

        return $this->editable || $oCurrentUser->isSuperAdmin();
    }

    /**
     * check if is deletable
     *
     * @global User $oCurrentUser
     * @return boolean
     */
    public function isDeletable()
    {
        $oCurrentUser = UserManager::getCurrentUser();

        return $this->deletable;
    }

    /**
     * get module
     *
     * @return Module
     */
    public function getModule()
    {
        if ($this->oModule === null) {
            $this->oModule = ModuleManager::getModuleById($this->moduleId);
        }

        return $this->oModule;
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
     * set deletable
     *
     * @param boolean $bDeletable
     */
    public function setDeletable($bDeletable)
    {
        $this->deletable = $bDeletable;
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

}
