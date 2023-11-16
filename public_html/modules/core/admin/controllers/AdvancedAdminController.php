<?php

class AdvancedAdminController extends AdminController
{
    /**
     * Constants action
     */
    const ACTION_ADD           = 'add';
    const ACTION_EDIT          = 'edit';
    const ACTION_DELETE        = 'delete';
    const ACTION_TOGGLE_ACTIVE = 'toggleActive';
    const ACTION_UPLOAD_IMAGE  = 'uploadImage';
    const ACTION_CROP_IMAGE    = 'cropImage';

    /**
     * @var string
     */
    protected static $module;

    /**
     * @var string
     */
    protected static $model;

    /**
     * @var string
     */
    protected static $manager;

    /**
     * @var bool
     */
    protected static $chainable = false;

    /**
     * @var int
     */
    protected static $level = 1;

    /**
     * @var int
     */
    protected static $maxImages = 0;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $delegateController;

    /**
     * @var string
     */
    protected $delegateAction;

    /**
     * @inheritdoc
     */
    protected static $allowed_actions = [
        self::ACTION_ADD,
        self::ACTION_EDIT,
        self::ACTION_DELETE,
        self::ACTION_TOGGLE_ACTIVE,
        self::ACTION_UPLOAD_IMAGE,
        self::ACTION_CROP_IMAGE,
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        $oPageLayout->sWindowTitle = sysTranslations::get(static::$module);
        $oPageLayout->sModuleName  = sysTranslations::get(static::$module);
        $oPageLayout->addJavascript(getSitePath('js/jsend.min.js', 'core'));

        Request::setParameterNames(
            [
                'ID',
                'SubController',
                'SubAction',
                'SubID',
                'SubSubController',
                'SubSubAction',
                'SubSubID',
            ]
        );

        switch (static::$level) {
            case 1:
                $this->controller = Request::getControllerSegment();
                $this->action     = Request::getActionSegment();
                $this->id         = Request::param('ID');
                break;
            case 2:
                $this->controller = Request::param('SubController');
                $this->action     = Request::param('SubAction');
                $this->id         = Request::param('SubID');
                break;
            case 3:
                $this->controller = Request::param('SubSubController');
                $this->action     = Request::param('SubSubAction');
                $this->id         = Request::param('SubSubID');
        }
    }

    /**
     * @inheritdoc
     */
    public function index()
    {
        $this->getRenderEngine()
            ->setVariables(
                [
                    $this->getModelsVariable() => call_user_func_array([static::$manager, 'get'], [true]),
                    'iPageCount'               => 1,
                    'iCurrPage'                => 1,
                    'iPerPage'                 => 'all',
                ]
            )
            ->getLayout()->sViewPath = $this->getView(__FUNCTION__);
    }

    /**
     * Add action
     *
     */
    public function add()
    {
        $this->getRenderEngine()
            ->setVariables(
                [
                    $this->getModelVariable() => new static::$model(),
                ]
            )
            ->getLayout()->sViewPath = $this->getView('edit');

        if (Request::isPost()) {
            $this->postAdd();
        }
    }

    /**
     * Post add action
     *
     */
    protected function postAdd()
    {
        $oModel = new static::$model();

        if ($this->postEdit($oModel)) {
            Router::redirect($this->getReturnUrl());
        }
    }

    /**
     * Edit action
     *
     */
    public function edit()
    {
        $oModel = call_user_func_array([static::$manager, 'getById'], [$this->id, true]);
        if (!$oModel) {
            showHttpError(404);
        }

        if ($this->isDelegatable()) {
            return $this->doDelegate();
        }

        if (Request::isPost()) {
            $this->postEdit($oModel);
        }

        $aVariables = [
            $this->getModelVariable() => $oModel,
        ];

        if (is_callable([$oModel, 'getImages'])) {
            $aVariables['oImageManagerHTML'] = $this->getImageManagerHTML(
                $oModel->{sprintf('%1$sId', StringHelper::toCamelCase(static::$model))},
                $oModel->getImages(),
                $this->getMediaUrl(null, 'crop-image', '', ''),
                $this->getMediaUrl(null, 'upload-image', '', ''),
                ['cms_thumb', 'detail', 'overview', 'crop_small', 'crop_detail'],
                static::$maxImages
            );
        }

        $this->getRenderEngine()
            ->setVariables($aVariables)
            ->getLayout()->sViewPath = $this->getView(__FUNCTION__);
    }

    /**
     * Post edit action
     *
     * @param Model $oModel
     *
     * @return mixed
     */
    protected function postEdit(Model $oModel)
    {
        $oModel->_load(Request::postVars(), false);

        if (!$oModel->isValid()) {
            $this->getRenderEngine()
                ->setVariables(
                    [
                        $this->getModelVariable() => $oModel,
                    ]
                );

            return false;
        }

        return call_user_func_array([static::$manager, 'save'], [$oModel]);
    }

    /**
     * Delete action
     *
     */
    public function delete()
    {
        $oModel = call_user_func_array([static::$manager, 'getById'], [$this->id, true]);

        if ($oModel && call_user_func_array([static::$manager, 'delete'], [$oModel])) {
            $_SESSION['statusUpdate'] = sysTranslations::get(sprintf('%1$s_deleted', static::$module)); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get(sprintf('%1$s_not_deleted', static::$module)); //save status update into session
        }

        Router::redirect($this->getReturnUrl());
    }

    /**
     * Upload image action
     *
     */
    public function uploadImage()
    {
        $oModel = call_user_func_array([static::$manager, 'getById'], [$iId = $this->id, true]);
        if (!$oModel) {
            Session::set('statusUpdate', sysTranslations::get('global_image_not_uploaded')); //error uploading file
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/edit/' . $iId);
        }

        $aImageSettings = TemplateSettings::get(static::$module, 'images');

        if ($_FILES) {
            $oUpload = new Upload(
                $_FILES['image'], $aImageSettings['imagesPath'] . "/" . $aImageSettings['originalReference'] . "/", (Request::postVar('title') != '' ? Request::postVar('title') : null), ['jpg', 'png', 'gif', 'jpeg'],
                true
            );

            if ($oUpload->bSuccess === true) {
                $oImage = ImageManager::handleImageUpload($oUpload, $aImageSettings, Request::postVar('title', ''), $aErrorMsgs);
                if ($oImage) {
                    call_user_func_array([static::$manager, 'saveImageRelation'], [$oModel->{sprintf('%1$sId', StringHelper::toCamelCase(static::$model))}, $oImage->imageId]);
                }

            } else {
                Session::set('statusUpdate', sysTranslations::get('global_image_not_uploaded') . $oUpload->getErrorMessage()); //error uploading file
            }
        } else {
            Session::set('statusUpdate', sysTranslations::get('global_image_not_uploaded')); //error uploading file
        }

        Router::redirect($this->getUrl(null, static::ACTION_EDIT));
    }

    /**
     * Crop image action
     *
     */
    public function cropImage()
    {
        Session::set('cropReferrer', $this->getUrl(null, static::ACTION_EDIT));

        $oImage = ImageManager::getImageById(
            $this->getRequest()
                ->getVar('imageId')
        );

        if (!$oImage) {
            Session::set('statusUpdate', sysTranslations::get('global_image_not_available')); //error getting image
            Router::redirect(Session::get('cropReferrer'));
        }

        // add setting to session in an array
        Session::set('aCropSettings', ImageManager::handleImageCropSettings($oImage, TemplateSettings::get('chatbot', 'images'), Session::get('cropReferrer'), 'bot bewerken'));

        Router::redirect(ADMIN_FOLDER . '/crop');
    }

    /**
     * Toggle active action
     *
     */
    public function toggleActive()
    {
        $oModel = call_user_func_array([static::$manager, 'getById'], [$this->id, true]);

        if ($oModel) {
            $oModel->online = !$oModel->online;
            call_user_func_array([static::$manager, 'save'], [$oModel]);
            $this->getRenderEngine()
                ->setVariables(
                    [
                        'id' => $this->id,
                    ]
                );
        }

        $this->json();
    }

    /**
     * Can we delegate to a different controller?
     *
     * @return bool
     */
    protected function isDelegatable()
    {
        if (!static::$chainable) {
            return false;
        }

        switch (static::$level) {
            case 1:
                $this->delegateController = StringHelper::toPascalCase(
                    sprintf(
                        '%1$s-admin-controller',
                        $this->getRequest()
                            ->param('SubController')
                    )
                );
                $this->delegateAction     = StringHelper::toCamelCase(
                    $this->getRequest()
                        ->param('SubAction')
                );
                break;
            case 2:
                $this->delegateController = StringHelper::toPascalCase(
                    sprintf(
                        '%1$s-admin-controller',
                        $this->getRequest()
                            ->param('SubSubController')
                    )
                );
                $this->delegateAction     = StringHelper::toCamelCase(
                    $this->getRequest()
                        ->param('SubSubAction')
                );
                break;
        }

        if ($this->delegateController && class_exists($this->delegateController) && $this->delegateAction) {
            return true;
        }

        return false;
    }

    /**
     * Delegate to a different controller
     *
     * @return mixed
     */
    protected function doDelegate()
    {
        return call_user_func_array([$this->delegateController, 'delegate'], [$this->delegateAction]);
    }

    /**
     * Retrieve the variable object name
     *
     * @return string
     */
    protected function getModelVariable()
    {
        return sprintf('o%1$s', static::$model);
    }

    /**
     * Retrieve the variable array name
     *
     * @return string
     */
    protected function getModelsVariable()
    {
        $sModel = static::$model;
        if (strrpos($sModel, 'y') == strlen($sModel)) {
            $sModel = sprintf('%1$sie', substr($sModel, 0, -1));
        }

        return sprintf('a%1$ss', $sModel);
    }

    /**
     * Retrieve the view based on the given view name and controller level
     *
     * @param $sView
     *
     * @return string
     */
    protected function getView($sView)
    {
        if (static::$level > 1) {
            $sView = sprintf(
                '%1$s%2$s%3$s',
                strtolower(static::$model),
                DIRECTORY_SEPARATOR,
                $sView
            );
        }

        return getAdminView($sView, static::$module);
    }

    /**
     * Retrieve an URL for use with the media mamnagers
     *
     * @param string $sController
     * @param string $sAction
     * @param string $sID
     * @param string $sRoot
     *
     * @return mixed|string
     */
    protected function getMediaUrl($sController = null, $sAction = null, $sID = null, $sRoot = null)
    {
        switch (static::$level) {
            case 3:
                $sUrl = $this->getLevel3Url($sController, $sAction, $sID, null, null, null, '', null, null, $sRoot);
                break;
            case 2:
                $sUrl = $this->getLevel2Url($sController, $sAction, $sID, '', null, null, $sRoot);
                break;
            case 1:
            default:
                $sUrl = $this->getLevel1Url('', $sAction, $sID, $sRoot);
                break;
        }

        return $this->sanitizeUrl($sUrl, true);
    }

    /**
     * Retrieve the URL for this exact resource
     *
     * @return mixed|string
     */
    protected function getUrl($sController = null, $sAction = null, $sID = null)
    {
        switch (static::$level) {
            case 3:
                $sUrl = $this->getLevel3Url($sController, $sAction, $sID);
                break;
            case 2:
                $sUrl = $this->getLevel2Url($sController, $sAction, $sID);
                break;
            case 1:
            default:
                $sUrl = $this->getLevel1Url($sController, $sAction, $sID);
                break;
        }

        return $this->sanitizeUrl($sUrl);
    }

    /**
     * Retrieve the return URL based on the level
     *
     * @return string
     */
    protected function getReturnUrl()
    {
        switch (static::$level) {
            case 3:
                $sUrl = $this->getLevel2Url();
                break;
            case 2:
                $sUrl = $this->getLevel1Url();
                break;
            case 1:
            default:
                $sUrl = $this->getLevel0Url();
                break;
        }

        return $this->sanitizeUrl($sUrl);
    }

    /**
     * Sanitize a given URL
     *
     * @param string $sUrl
     * @param bool   $bTrim
     *
     * @return mixed|string
     */
    protected function sanitizeUrl($sUrl, $bTrim = false)
    {
        while (strpos($sUrl, '//') !== false) {
            $sUrl = str_replace('//', '/', $sUrl);

        }

        if ($bTrim) {
            $sUrl = trim($sUrl, '/');
        }

        return $sUrl;
    }

    /**
     * Retrieve a URL for a level 3 controller
     *
     * @param string $sSubSubController
     * @param string $sSubSubAction
     * @param string $sSubSubID
     * @param string $sSubController
     * @param string $sSubAction
     * @param string $sSubID
     * @param string $sController
     * @param string $sAction
     * @param string $sID
     * @param string $sRoot
     *
     * @return string
     */
    protected function getLevel3Url($sSubSubController = null, $sSubSubAction = null, $sSubSubID = null, $sSubController = null, $sSubAction = null, $sSubID = null, $sController = null, $sAction = null, $sID = null, $sRoot = null)
    {
        return sprintf(
            '%1$s/%2$s/%3$s/%4$s',
            $this->getLevel2Url($sSubController, $sSubAction, $sSubID, $sController, $sAction, $sID, $sRoot),
            !is_null($sSubSubController) ? $sSubSubController : Request::param('SubSubController'),
            !is_null($sSubSubAction) ? $sSubSubAction : Request::param('SubSubAction'),
            !is_null($sSubSubID) ? $sSubSubID : Request::param('SubSubID')
        );
    }

    /**
     * Retrieve  a URL for a level 2 controller
     *
     * @param string $sSubController
     * @param string $sSubAction
     * @param string $sSubID
     * @param string $sController
     * @param string $sAction
     * @param string $sID
     * @param string $sRoot
     *
     * @return string
     */
    protected function getLevel2Url($sSubController = null, $sSubAction = null, $sSubID = null, $sController = null, $sAction = null, $sID = null, $sRoot = null)
    {
        return sprintf(
            '%1$s/%2$s/%3$s/%4$s',
            $this->getLevel1Url($sController, $sAction, $sID, $sRoot),
            !is_null($sSubController) ? $sSubController : Request::param('SubController'),
            !is_null($sSubAction) ? $sSubAction : Request::param('SubAction'),
            !is_null($sSubID) ? $sSubID : Request::param('SubID')
        );
    }

    /**
     * Retrieve the URL for a level 1 controller
     *
     * @param string $sController
     * @param string $sAction
     * @param string $sID
     * @param string $sRoot
     *
     * @return string
     */
    protected function getLevel1Url($sController = null, $sAction = null, $sID = null, $sRoot = null)
    {
        return sprintf(
            '%1$s/%2$s/%3$s',
            $this->getLevel0Url($sController, $sRoot),
            !is_null($sAction) ? $sAction : Request::getActionSegment(),
            !is_null($sID) ? $sID : Request::param('ID')
        );
    }

    /**
     * Retrieve a URL for a level 1 controller, but only the basics
     *
     * @param string $sController
     * @param string $sRoot
     *
     * @return string
     */
    protected function getLevel0Url($sController = null, $sRoot = null)
    {
        return sprintf(
            '%1$s/%2$s',
            !is_null($sRoot) ? $sRoot : ADMIN_FOLDER,
            !is_null($sController) ? $sController : Request::getControllerSegment()
        );
    }
}