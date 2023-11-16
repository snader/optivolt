<?php

class VideoLinkManagerHTML extends Model
{

    public $onlineChangeable     = true; // videoLinks can be set online, offline
    public $changeOnlineLink     = null; // send data to link
    public $editable             = true; // videoLinks are editable
    public $editLink             = null; // send data to link
    public $deletable            = true; // videoLinks are deletable
    public $deleteLink           = null; // send data to link
    public $iContainerIDAddition = 1; // unique addition for the imageManager container
    public $sUploadUrl           = null; // uploadUrl
    public $sEditVideoLinkFormLocation; // location of the edit form which will be included once
    public $sortable             = true; // videoLinks are sortable
    public $saveOrderLink        = null; // link to save videoLinks
    public $sHiddenAction        = 'saveVideoLink'; // hidden form field with name `action`
    public $template             = null; // template for managing videoLinks
    public $iMaxVideoLinks       = null; // determine how much video's may be linked
    public $aVideoLinks          = []; // links for displaying under form

    public function __construct(array $aData = [], $bStripTags = true)
    {
        # set default links, doesn't work with setting properties directly (constants show erors)
        $this->changeOnlineLink           = ADMIN_FOLDER . '/videoLinkManagement/ajax-setOnline/';
        $this->editLink                   = ADMIN_FOLDER . '/videoLinkManagement/ajax-edit/';
        $this->deleteLink                 = ADMIN_FOLDER . '/videoLinkManagement/ajax-delete';
        $this->saveOrderLink              = ADMIN_FOLDER . '/videoLinkManagement/ajax-saveOrder';
        $this->template                   = getAdminSnippet('videoLinkManagerHTML', 'videoLinkManager');
        $this->sEditVideoLinkFormLocation = getAdminSnippet('editVideoLinkForm', 'videoLinkManager');

        parent::__construct($aData, $bStripTags);
    }

    /**
     * validate object
     */
    public function validate()
    {
        if ($this->onlineChangeable && empty($this->changeOnlineLink)) {
            $this->setPropInvalid('changeOnlineLink');
        }
        if ($this->editable && empty($this->editLink)) {
            $this->setPropInvalid('editLink');
        }
        if ($this->deletable && empty($this->deleteLink)) {
            $this->setPropInvalid('deleteLink');
        }
        if ($this->sortable && empty($this->saveOrderLink)) {
            $this->setPropInvalid('saveOrderLink');
        }
        if (empty($this->template)) {
            $this->setPropInvalid('template');
        }
        if (empty($this->sUploadUrl)) {
            $this->setPropInvalid('sUploadUrl');
        }
        if (empty($this->iContainerIDAddition)) {
            $this->setPropInvalid('iContainerIDAddition');
        }
        if (empty($this->sHiddenAction)) {
            $this->setPropInvalid('sHiddenAction');
        }
    }

    /**
     * include template for managing VideoLinks
     *
     * @global string $oPageLayout
     */
    public function includeTemplate()
    {
        global $oPageLayout; // pageLayout is needed here for javascript adding
        if (!$oPageLayout) {
            $oPageLayout = CoreController::getCurrent()
                ->getRenderEngine()
                ->getLayout(); // pageLayout is needed here for javascript adding
        }

        if ($this->isValid()) {
            include $this->template;
        } else {
            echo '<b>' . sysTranslations::get('classes_config_missing') . '</b>';
            echo '<ul class="standard">';
            if (!$this->isPropValid('changeOnlineLink')) {
                echo '<li>' . sysTranslations::get('classes_changeOnlineLink_not_set') . '</li>';
            }
            if (!$this->isPropValid('editLink')) {
                echo '<li' . sysTranslations::get('classes_editLink_not_set') . '</li>';
            }
            if (!$this->isPropValid('deleteLink')) {
                echo '<li>' . sysTranslations::get('classes_deleteLink_not_set') . '</li>';
            }
            if (!$this->isPropValid('saveOrderLink')) {
                echo '<li>' . sysTranslations::get('classes_saveOrderLink_not_set') . '</li>';
            }
            if (!$this->isPropValid('template')) {
                echo '<li>' . sysTranslations::get('classes_template_not_set') . '</li>';
            }
            if (!$this->isPropValid('sUploadUrl')) {
                echo '<li>' . sysTranslations::get('classes_sUploadUrl_not_set') . '</li>';
            }
            if (!$this->isPropValid('iContainerIDAddition')) {
                echo '<li>' . sysTranslations::get('classes_iContainerIDAddition_not_set') . '</li>';
            }
            if (!$this->isPropValid('sHiddenAction')) {
                echo '<li>' . sysTranslations::get('classes_sHiddenAction_not_set') . '</li>';
            }
            echo '</ul>';
        }
    }

}

?>