<?php

class User extends Model
{
    const IMAGES_PATH = '/uploads/images/user';

    public    $userId            = null;
    public    $name; //name of the user
    public    $username; //username for login
    public    $password; //password for login
    private   $administrator     = 0; //user is full Administrator user
    public    $accountmanager    = 0;
    private   $seo               = 0; //user is full SEO user
    public    $userAccessGroupId = null; //user group
    public    $imageId           = null;
    private   $oImage            = null;
    public    $systemLanguageId  = null; //user language
    private   $oUserAccessGroup  = null;
    public    $created; //created timestamp
    public    $modified; //last modified timestamp
    public    $locked; // locked timestamp
    public    $lockedReason; // reason for getting locked
    public    $deactivation      = 0;
    public    $deactivationDate;
    public    $twoStepEnabled    = 1; // is 2-step required
    public    $twoStepSecret; // 2-step secret secret
    public    $twoStepCookie = 0;
    public    $twoStepSecretVerified; // 2-step secret secret verified
    private   $oLanguage         = null;
    protected $onlineChangeable  = 1; //online changable is true by default

    public  $lastLogin;

    private $properties = [];

    public function __set($name, $value) {
        $this->properties[$name] = $value;
    }

    public function __get($name) {
        return $this->properties[$name] ?? null;
    }

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->username)) {
            $this->setPropInvalid('username');
        }
        if (empty($this->password)) {
            $this->setPropInvalid('password');
        }
        if (empty($this->systemLanguageId)) {
            $this->setPropInvalid('systemLanguageId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
        $oUser = UserManager::getUserByUsername($this->username);
        if ($oUser && $oUser->userId != $this->userId) {
            $this->setPropInvalid('username');
        }
    }

    /**
     * mask pasword for session use
     */
    function maskPass()
    {
        $this->password = "XXX";
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

        $aModules = ModuleManager::getModulesByFilter();

        return $this->getUserAccessGroup()
            ->hasRightsForModule($sModuleName);
    }

    /**
     * check if user has rights to access module by controller segment
     *
     * @param $sControllerSegment
     */
    public function hasRightsForModuleByControllerSegment($sControllerSegment)
    {

        $aAdminControllerRoutes = json_decode(FileSystem::read(DOCUMENT_ROOT . '/init/adminRoutes.json'), true);

        if (array_key_exists($sControllerSegment, $aAdminControllerRoutes)) {

            $aAdminControllerRoute = $aAdminControllerRoutes[$sControllerSegment];
            $aExceptions           = [
                'core',
                'login',
                'imageManager',
                'crop',
                'fileManager',
                'linkManager',
                'videoLinkManager',
                'autocomplete',
            ];

            /* other controllersegment for other module */
            //if ($sControllerSegment == 'locaties') {
            //    $sControllerSegment = 'klanten';
           // }

            //Check the exceptions on this rule, these are controllers that don't have their own module
            if (!in_array($aAdminControllerRoute['module'], $aExceptions)) {

                //Check the module based on the name defined in the admin routes (these differ from the actual module names)
                return $this->hasRightsForModule($sControllerSegment); //
            }
        }

        //we return true because the module was not found, so we assume it's a route without a module dependency
        return true;
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

        return $this->getUserAccessGroup()
            ->hasRightsForModuleAction($sModuleAction);
    }

    /**
     * check if user has rights for user group
     *
     * @param string $sUserAccessGroupName
     *
     * @return boolean
     */
    public function hasUserAccessGroup($sUserAccessGroupName)
    {

        return $this->getUserAccessGroup()->name == $sUserAccessGroupName;
    }

    /**
     * return if user has administrator rights
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->administrator;
    }

    /**
     * return if user has SEO rights
     *
     * @return boolean
     */
    public function isSEO()
    {
        return $this->seo;
    }

    /**
     * set administrator
     *
     * @return boolean
     */
    public function setAdministrator($bAdministartor)
    {
        $this->administrator = $bAdministartor;
    }

    /**
     * set seo
     *
     * @return boolean
     */
    public function setSEO($bSeo)
    {
        $this->seo = $bSeo;
    }

    /**
     * get administrator value
     *
     * @return type
     */
    public function getAdministrator()
    {
        return $this->administrator;
    }

    /**
     * get seo value
     *
     * @return type
     */
    public function getSeo()
    {
        return $this->seo;
    }


    public function getAccountmanager()
    {
        return $this->accountmanager;
    }

    /**
     * check is user is editable
     *
     * @return boolean
     * @global User $oCurrentUser
     */
    public function isEditable()
    {
        $oCurrentUser = UserManager::getCurrentUser();

        // user is admin (LandgoedVoorn), and logged in user is not
        if ($this->hasUserAccessGroup(UserAccessGroup::userAccessGroup_administrators) && !$oCurrentUser->isSuperAdmin()) {
            return false;
        }

        // user editing is admin (Client), logged in user is not
        if ($this->hasUserAccessGroup(UserAccessGroup::userAccessGroup_administrators_client) && !$oCurrentUser->isClientAdmin() && !$oCurrentUser->isSuperAdmin()) {
            return false;
        }

        return true;
    }

    /**
     * check if user is deletable
     *
     * @return boolean
     * @global User $oCurrentUser
     */
    public function isDeletable()
    {
        $oCurrentUser = UserManager::getCurrentUser();

        // user is admin (LandgoedVoorn), cannot delete at all
        if ($this->hasUserAccessGroup(UserAccessGroup::userAccessGroup_administrators)) {
            return false;
        }

        // user deleting is admin (Client), logged in user is not
        if ($this->hasUserAccessGroup(UserAccessGroup::userAccessGroup_administrators_client) && !$oCurrentUser->isSuperAdmin()) {
            return false;
        }

        return true;
    }

    /**
     * check if user is superadmin
     *
     * @return boolean
     * @deprecated use isSuperAdmin
     */
    public function isAsideAdmin()
    {
        return $this->isSuperAdmin();
    }

    /**
     * check if user is superadmin
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->hasUserAccessGroup(UserAccessGroup::userAccessGroup_administrators);
    }

    /**
     * check if user is normal admin
     *
     * @return boolean
     */
    public function isClientAdmin()
    {
        return ($this->hasUserAccessGroup(UserAccessGroup::userAccessGroup_administrators_client));
    }

    /**
     * check if user is normal admin
     *
     * @return boolean
     */
    public function isEngineer()
    {
        return ($this->hasUserAccessGroup(UserAccessGroup::userAccessGroup_engineer));
    }
    

    /**
     * check if user is normal admin
     *
     * @return boolean
     */
    public function isClientAdminLimited()
    {
        return ($this->hasUserAccessGroup(UserAccessGroup::userAccessGroup_administrators_client_lim));
    }

    /**
     * get language
     *
     * @return SystemLanguage
     */
    public function getLanguage()
    {
        if ($this->oLanguage === null) {
            $this->oLanguage = SystemLanguageManager::getLanguageById($this->systemLanguageId);
        }

        return $this->oLanguage;
    }

    /**
     * get user group
     *
     * @param boolean $bForceQuery force to renew object
     *
     * @return UserAccessGroup
     */
    public function getUserAccessGroup($bForceQuery = false)
    {
        if ($this->oUserAccessGroup === null || $bForceQuery) {
            $this->oUserAccessGroup = UserAccessGroupManager::getUserAccessGroupById($this->userAccessGroupId);
        }

        return $this->oUserAccessGroup;
    }

    /**
     * Set Google auth code to user
     */
    public function setGoogleAuthCode($sGoogleAuthCode)
    {
        $this->twoStepSecret = $sGoogleAuthCode;
        UserManager::updateUserGoogleAuthCode($this->userId, $sGoogleAuthCode);
    }

    /**
     * @param bool $bForceQuery
     *
     * @return \Image|null
     */
    public function getImage($bForceQuery = false)
    {
        if ($this->oImage === null || $bForceQuery) {
            if (is_numeric($this->imageId)) {
                $this->oImage = ImageManager::getImageById($this->imageId);
            }
        }

        return $this->oImage;
    }

    /**
     * check if user is online changeable
     *
     * @return integer
     */
    public function isOnlineChangeable()
    {
        return $this->userAccessGroupId;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->name;
    }
}

?>
