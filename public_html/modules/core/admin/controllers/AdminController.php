<?php

abstract class AdminController extends CoreController
{
    /**
     * Current user
     *
     * @var User
     */
    protected $currentUser;

    /**
     * AdminController constructor.
     *
     */
    public function __construct()
    {
        array_unshift(static::$allowed_actions, 'index');
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->getRenderEngine()
            ->setVariables(
                [
                    'aLocales' => LocaleManager::getLocalesByFilter(['showAll' => true]),
                ]
            )->getLayout()->addJavascript(getSitePath('js/jsend.min.js'));
    }

    /**
     * Set the current user
     *
     * @param User $oCurrentUser
     */
    public function setCurrentUser($oCurrentUser)
    {
        $this->getRenderEngine()
            ->setVariables(
                [
                    'oCurrentUser' => $this->currentUser = $oCurrentUser,
                ]
            );
    }

    /**
     * Retrieve the current user
     *
     * @return \User
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * @inheritdoc
     */
    public function render($template = null, $module = 'core')
    {
        switch (static::$render) {
            case static::RENDER_TRUE:
                $this->getRenderEngine()
                    ->setVariables(
                        [
                            'oController' => $this,
                        ]
                    )
                    ->setTemplate('layout');

                return $this->getRenderEngine()
                    ->render($template, true, $module);
            default:
                // do not disclose this information over ajax
                $this->getRenderEngine()
                    ->setVariables(['oCurrentUser' => null]);

                return parent::render($template, $module);
        }
    }

    /**
     * Retrieve the HTML for the image manager
     *
     * @param int    $iObjectId
     * @param array  $aImages
     * @param string $sCropSegment
     * @param string $sUploadSegment
     * @param array  $aReferences
     * @param int    $iMaxImages
     *
     * @return ImageManagerHTML
     *
     * @note legacy
     */
    protected function getImageManagerHTML($iObjectId, array $aImages, $sCropSegment, $sUploadSegment, array $aReferences, $iMaxImages = 0)
    {
        $oImageManagerHTML                             = new ImageManagerHTML();
        $oImageManagerHTML->aImages                    = $aImages;
        $oImageManagerHTML->cropLink                   = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/' . $sCropSegment . '/' . $iObjectId;
        $oImageManagerHTML->sUploadUrl                 = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/' . $sUploadSegment . '/' . $iObjectId;
        $oImageManagerHTML->aNeededImageFileReferences = $aReferences;
        $oImageManagerHTML->bShowCropAfterUploadOption = false;
        $oImageManagerHTML->iMaxImages                 = $iMaxImages;

        return $oImageManagerHTML;
    }

    /**
     * Retrieve the HTML for the file manager
     *
     * @param int    $iObjectId
     * @param array  $aFiles
     * @param string $sUploadSegment
     *
     * @return \FileManagerHTML
     *
     * @note legacy
     */
    protected function getFileManagerHTML($iObjectId, array $aFiles, $sUploadSegment)
    {
        $oFileManagerHTML             = new FileManagerHTML();
        $oFileManagerHTML->aFiles     = $aFiles;
        $oFileManagerHTML->sUploadUrl = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/' . $sUploadSegment . '/' . $iObjectId;

        return $oFileManagerHTML;
    }

    /**
     * Retrieve the HTML for the link manager
     *
     * @param int    $iObjectId
     * @param array  $aLinks
     * @param string $sUploadSegment
     *
     * @return \LinkManagerHTML
     *
     * @note legacy
     */
    protected function getLinkManagerHTML($iObjectId, array $aLinks, $sUploadSegment)
    {
        $oLinkManagerHTML             = new LinkManagerHTML();
        $oLinkManagerHTML->aLinks     = $aLinks;
        $oLinkManagerHTML->sUploadUrl = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/' . $sUploadSegment . '/' . $iObjectId;

        return $oLinkManagerHTML;
    }

    /**
     * Retrieve the HTML for the video link manager
     *
     * @param int    $iObjectId
     * @param array  $aLinks
     * @param string $sUploadSegment
     *
     * @return \VideoLinkManagerHTML
     *
     * @note legacy
     */
    protected function getVideoLinkManagerHTML($iObjectId, array $aLinks, $sUploadSegment)
    {
        $oVideoLinkManagerHTML              = new VideoLinkManagerHTML();
        $oVideoLinkManagerHTML->aVideoLinks = $aLinks;
        $oVideoLinkManagerHTML->sUploadUrl  = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/' . $sUploadSegment . '/' . $iObjectId;

        return $oVideoLinkManagerHTML;
    }
}