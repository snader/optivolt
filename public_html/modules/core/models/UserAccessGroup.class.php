<?php

/* Models and managers used by the UserAccessGroup model */
require_once 'Model.class.php';

class UserAccessGroup extends Model
{

    const userAccessGroup_administrators        = 'administrators'; // LandgoedVoorn users
    const userAccessGroup_administrators_client = 'administrators_client'; // Client admin users
    const userAccessGroup_administrators_client_lim = 'administrators_client_limited'; // Client admin users beperkt
    const userAccessGroup_engineer              = 'clients';

    public  $userAccessGroupId = null;
    public  $displayName; //name to show in overviews
    public  $name; // name to use for checking in code
    public  $created; // created timestamp
    public  $createdBy; // created by
    public  $modified; // last modified timestamp
    public  $modifiedBy; // last modified by
    private $editable          = 1; //editable 1 by default
    private $deletable         = 1; //deletable 1 by default
    private $aModuleActions    = null;
    private $aModules          = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->displayName)) {
            $this->setPropInvalid('displayName');
        }
        if (!is_numeric($this->editable)) {
            $this->setPropInvalid('editable');
        }
        if (!is_numeric($this->deletable)) {
            $this->setPropInvalid('deletable');
        }
    }

    /**
     * check if user group is editable
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
     *
    */
    public function isDeletable()
    {
        $aUsers = UserManager::getUsersByFilter(['userAccessGroupId' => $this->userAccessGroupId ? $this->userAccessGroupId : -1]);

        return empty($aUsers) && $this->deletable && $this->name != self::userAccessGroup_administrators && $this->name != self::userAccessGroup_administrators_client;
    }

    /**
     * check if name is changeable
     *
     * @global User $oCurrentUser
     * @return boolean
     */
    public function isNameChangeable()
    {
        $oCurrentUser = UserManager::getCurrentUser();

        return !in_array(
                $this->name,
                [
                    self::userAccessGroup_administrators,
                ]
            ) && $oCurrentUser->isSuperAdmin();
    }

    /**
     * get user moduleActions
     *
     * @return array of moduleAction objects
     */
    public function getModuleActions()
    {
        if ($this->aModuleActions === null) {
            $this->aModuleActions = ModuleActionManager::getModuleActionsByFilter(['userAccessGroupId' => $this->userAccessGroupId ? $this->userAccessGroupId : -1]);
        }

        return $this->aModuleActions;
    }

    /**
     * set user moduleActions
     *
     * @param array of moduleAction objects
     */
    public function setModuleActions(array $aModuleActions)
    {
        $this->aModuleActions = $aModuleActions;
    }

    /**
     * check if user has rights to see moduleAction
     *
     * @param string $sModuleAction
     *
     * @return boolean
     */
    public function hasRightsForModuleAction($sModuleAction)
    {

        $bResult = false;
        foreach ($this->getModuleActions() AS $oModuleAction) {
            if ($oModuleAction->name == $sModuleAction) {
                $bResult = true;
                break;
            }
        }

        return $bResult;
    }

    /**
     * check if user has rights to see module
     *
     * @param string $sModuleName
     *
     * @return boolean
     */
    public function hasRightsForModule($sModuleName)
    {

        $bResult = false;
        foreach ($this->getModules('active-all') AS $oModule) {

            if (strtolower($oModule->name ?? '') == strtolower($sModuleName ?? '')) {
                $bResult = true;
                break;
            }
        }
        return $bResult;
    }

    /**
     * get modules for a user access group (for menu and global rights)
     *
     * @return array of module objects
     */
    public function getModules($sList = 'active-menu')
    {
        if (!isset($this->aModules[$sList])) {
            switch ($sList) {
                case 'active-menu':
                    $this->aModules[$sList] = ModuleManager::getModulesByFilter(['parentModuleId' => -1, 'userAccessGroupId' => ($this->userAccessGroupId ? $this->userAccessGroupId : -1)]);
                    break;
                case 'active-all':
                    $this->aModules[$sList] = ModuleManager::getModulesByFilter(['showAll' => 1, 'active' => 1, 'userAccessGroupId' => ($this->userAccessGroupId ? $this->userAccessGroupId : -1)]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aModules[$sList];
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
