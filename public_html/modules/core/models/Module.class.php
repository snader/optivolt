<?php

class Module extends Model
{

    public  $moduleId;
    public  $name; //technical name of the module
    public  $linkName; //name of the module
    public  $collapseName; // name of menu item if collapsable
    public  $icon; //icon of the module
    public  $showInMenu     = 1; //show module in menu
    public  $parentModuleId; //parent module id
    public  $order          = 1000; //order to display the modules
    public  $created; //created timestamp
    public  $modified; //last modified timestamp
    private $active         = 1; // module is active or not, only for checks in queries, updatet via settings controller
    private $oParent        = null; //parent module
    private $aChildren      = null; //child modules
    private $aModuleActions = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->name) && empty($this->moduleId)) {
            $this->setPropInvalid('name');
        }
        if (empty($this->linkName)) {
            $this->setPropInvalid('linkName');
        }
        if (!is_numeric($this->order)) {
            $this->setPropInvalid('order');
        }
    }

    /**
     * get the parent module
     *
     * @return Module
     */
    public function getParent()
    {
        if ($this->oParent === null) {
            $this->oParent = ModuleManager::getModuleById($this->parentModuleId);
        }

        return $this->oParent;
    }

    /**
     * check if the module has a parent module
     *
     * @return bool
     */
    public function hasParent()
    {
        return $this->parentModuleId !== null;
    }

    /**
     * check if the module has a child module
     *
     * @return bool
     */
    public function hasChildren($sList = 'active-menu')
    {
        return count($this->getChildren($sList)) > 0;
    }

    /**
     * get all children of a module
     *
     * @return array Module
     */
    public function getChildren($sList = 'active-menu')
    {
        if (!isset($this->aChildren[$sList])) {
            switch ($sList) {
                case 'active-menu':
                    $this->aChildren[$sList] = ModuleManager::getModulesByFilter(['parentModuleId' => $this->moduleId]);
                    break;
                case 'active-all':
                    $this->aChildren[$sList] = ModuleManager::getModulesByFilter(['parentModuleId' => $this->moduleId, 'active' => 1, 'showAll' => 1]);
                    break;
                case 'all':
                    $this->aChildren[$sList] = ModuleManager::getModulesByFilter(['parentModuleId' => $this->moduleId, 'showAll' => 1]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aChildren[$sList];
    }

    /**
     * check if module is a child of this module
     *
     * @param string $sModuleName
     *
     * @return bool
     */
    public function hasChild($sModuleName)
    {
        foreach ($this->getChildren() AS $oChild) {
            if ($oChild->name == $sModuleName) {
                return true;
            }
        }

        return false;
    }

    /**
     * get user moduleActions
     *
     * @return array of moduleAction objects
     */
    public function getModuleActions()
    {
        if ($this->aModuleActions === null) {
            $this->aModuleActions = ModuleActionManager::getModuleActionsByFilter(['showAll' => 1, 'moduleId' => $this->moduleId ? $this->moduleId : -1]);
        }

        return $this->aModuleActions;
    }

}
