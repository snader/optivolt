<?php

// check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('pages_manager');
$oPageLayout->sModuleName  = sysTranslations::get('pages_manager');
// max levels for page structure depth
$iMaxLevels = Settings::get('pagesMaxLevels');

// reset crop settings
unset($_SESSION['aCropSettings']);

// get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

// handle adding BBs
if (Request::param('ID') == 'add-bb' && CSRFSynchronizerToken::validate()) {
    PageManager::savePageBrandboxItemRelation(Request::postVar('pageId'), Request::postVar('brandboxItemId'));
    Session::set('statusUpdate', sysTranslations::get('brandbox_item_saved')); //save status update into session
    Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . Request::postVar('pageId'));
}

// handle remove BBs
if (Request::param('ID') == 'remove-bb' && CSRFSynchronizerToken::validate()) {
    PageManager::deletePageBrandboxItemRelation(Request::param('OtherID'), Request::param('AnotherID'));
    Session::set('statusUpdate', sysTranslations::get('brandbox_item_deleted')); //save status update into session
    Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . Request::param('OtherID'));
}

// handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

    // set crop referrer for pages module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oPage = PageManager::getPageById(http_get("param2"));
        if (!$oPage) {
            http_redirect(ADMIN_FOLDER . "/");
        }
        // is editable?
        if (!$oPage->isEditable()) {
            $_SESSION['statusUpdate'] = sysTranslations::get('pages_not_editable_2'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
        }
    } else {
        $oPage = new Page();
        if (http_get("parentPageId")) {
            $oParentPage = PageManager::getPageById(http_get("parentPageId"));
            if (!$oParentPage || !$oParentPage->mayHaveSub() || $oParentPage->level >= $iMaxLevels) {
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
            }
            $oPage->copyFromParent($oParentPage);
        }

        // Set languageId
        $oPage->languageId = AdminLocales::language();
    }

    // action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        // load data in object
        $oPage->_load($_POST);

        // set content after load, replacing internal absolute links with relative inks, _load strips tags for all inputs
        $oPage->intro   = convertAbsToRelLinks(http_post('intro'));
        $oPage->content = convertAbsToRelLinks(http_post('content'));

        if ($oCurrentUser->isSEO()) {
            $oPage->setUrlPart(http_post('urlPart'));
            $oPage->setInMenu(http_post('inMenu'));
            $oPage->setInFooter(http_post('inFooter'));
            $oPage->setIndexable(http_post('indexable'));
            $oPage->setIncludeParentInUrlPath(http_post('includeParentInUrlPath'));
            $oPage->setUrlParameters(http_post('urlParameters'));
        }

        if ($oCurrentUser->isAdmin()) {
            $oPage->setOnlineChangeable(http_post('onlineChangeable'));
            $oPage->setControllerPath(http_post('controllerPath'));
            $oPage->setEditable(http_post('editable'));
            $oPage->setDeletable(http_post('deletable'));
            $oPage->setMayHaveSub(http_post('mayHaveSub'));
            $oPage->setLockUrlPath(http_post('lockUrlPath'));
            $oPage->setLockParent(http_post('lockParent'));
            $oPage->setHideImageManagement(http_post('hideImageManagement'));
            $oPage->setHideFileManagement(http_post('hideFileManagement'));
            $oPage->setHideLinkManagement(http_post('hideLinkManagement'));
            $oPage->setHideVideoLinkManagement(http_post('hideVideoLinkManagement'));
            $oPage->setHideBrandboxManagement(http_post('hideBrandboxManagement'));
            $oPage->setHideFormManagement(http_post('hideFormManagement'));
        }

        // if object is valid, save
        if ($oPage->isValid()) {
            PageManager::savePage($oPage); //save page
            $_SESSION['statusUpdate'] = sysTranslations::get('pages_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId);
        } else {
            Debug::logError("", "Pages module php validate error", __FILE__, __LINE__, "Tried to save Page with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('pages_not_saved');
        }
    }

    // action saveImage
    if (http_post("action") == 'saveImage') {
        if (CSRFSynchronizerToken::validate()) {
            $bCheckMime = true;

            // for upload from MFUpload
            if (http_post("MFUpload")) {
                $oResObj          = new stdClass();
                $oResObj->success = false;
                $bCheckMime       = true;
            }

            $aImageSettings = TemplateSettings::get('pages', 'images');

            // upload file or return error
            $oUpload = new Upload($_FILES['image'], $aImageSettings['imagesPath'] . "/" . $aImageSettings['originalReference'] . "/", (http_post('title') != '' ? http_post('title') : null), ['jpg', 'png', 'gif', 'jpeg'], $bCheckMime);

            // save image to database on success
            if ($oUpload->bSuccess === true) {
                $sTitle = http_post('title', '');
                $oImage = ImageManager::handleImageUpload($oUpload, $aImageSettings, $sTitle, $aErrorMsgs);

                if ($oImage) {
                    // save page image relation
                    PageManager::savePageImageRelation($oPage->pageId, $oImage->imageId);
                }

                // for MFUpload
                if (http_post("MFUpload")) {
                    $oResObj->success = true;
                    $oImageFileThumb  = $oImage->getImageFileByReference('cms_thumb');
                    // add some extra values
                    $oImageFileThumb->isOnlineChangeable  = $oImage->isOnlineChangeable();
                    $oImageFileThumb->isCropable          = $oImage->isCropable();
                    $oImageFileThumb->isEditable          = $oImage->isEditable();
                    $oImageFileThumb->isDeletable         = $oImage->isDeletable();
                    $oImageFileThumb->hasNeededImageFiles = $oImage->hasImageFiles(TemplateSettings::get('pages', 'images/neededImageFiles'));

                    $oResObj->imageFile = $oImageFileThumb; // for adding image to list (last imageFile object)
                    print json_encode($oResObj);
                    die;
                }

                // back to edit
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId);
            } else {

                // for MFUpload
                if (http_post("MFUpload")) {
                    $oResObj->errorMsg = $oUpload->getErrorMessage();
                    print json_encode($oResObj);
                    die;
                }

                $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_uploaded') . $oUpload->getErrorMessage(); //error uploading file
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId);
            }
        }

        if (http_post('MFUpload')) {
            die(json_encode(['success'=>false]));
        }
    }
    // action saveFile
    if (http_post("action") == 'saveFile') {
        if (CSRFSynchronizerToken::validate()) {

            $bCheckMime = true;

            // for upload from MFUpload
            if (http_post("MFUpload")) {
                $oResObj          = new stdClass();
                $oResObj->success = false;
                $bCheckMime       = true;
            }

            // upload file or return error
            $oUpload = new Upload($_FILES['file'], Page::FILES_PATH . '/', (http_post('file_title') != '' ? http_post('file_title') : null), null, $bCheckMime);

            // save file to database on success
            if ($oUpload->bSuccess === true) {

                // make File object and save
                $oFile           = new File();
                $oFile->name     = $oUpload->sNewFileBaseName;
                $oFile->mimeType = $oUpload->sMimeType;
                $oFile->size     = $oUpload->iSize;
                $oFile->link     = $oUpload->sNewFilePath;
                $oFile->title    = http_post('title') == '' ? $oUpload->sFileName : http_post('title');

                // save file
                FileManager::saveFile($oFile);

                // save page file relation
                PageManager::savePageFileRelation($oPage->pageId, $oFile->mediaId);

                // for MFUpload
                if (http_post("MFUpload")) {
                    $oResObj->success = true;
                    // add some extra values
                    $oFile->isOnlineChangeable = $oFile->isOnlineChangeable();
                    $oFile->isEditable         = $oFile->isEditable();
                    $oFile->isDeletable        = $oFile->isDeletable();
                    $oFile->extension          = $oFile->getExtension();

                    $oResObj->file = $oFile; // for adding file to list
                    print json_encode($oResObj);
                    die;
                }

                // back to edit
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId);
            } else {

                // for MFUpload
                if (http_post("MFUpload")) {
                    $oResObj->errorMsg = $oUpload->getErrorMessage();
                    print json_encode($oResObj);
                    die;
                }

                $_SESSION['statusUpdate'] = sysTranslations::get('global_file_not_uploaded') . $oUpload->getErrorMessage(); //error uploading file
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId);
            }
        }

        if (http_post('MFUpload')) {
            die(json_encode(['success'=>false]));
        }
    }

    // action = saveLink
    if (http_post("action") == 'saveLink' && CSRFSynchronizerToken::validate()) {

        // load data in object
        $oLink = new Link();
        $oLink->_load($_POST);

        // if object is valid, save
        if ($oLink->isValid()) {
            LinkManager::saveLink($oLink); //save link
            PageManager::savePageLinkRelation($oPage->pageId, $oLink->mediaId);
            $_SESSION['statusUpdate'] = sysTranslations::get('global_link_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId);
        } else {
            Debug::logError("", "Page module php validate error", __FILE__, __LINE__, "Tried to save Link with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('global_link_not_saved');
        }
    }

    // action = saveVideoLink
    if (http_post("action") == 'saveVideoLink' && CSRFSynchronizerToken::validate()) {
        if ($oVideoLink = VideoLinkManager::saveVideoLink(Request::postVars())) {
            PageManager::savePageVideoLinkRelation($oPage->pageId, $oVideoLink->mediaId);
            $_SESSION['statusUpdate'] = sysTranslations::get('global_video_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId);
        } else {
            Debug::logError("", "Page module php validate error", __FILE__, __LINE__, "Tried to save Link with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('global_video_not_saved');
        }
    }

    // set settings for image management
    $oImageManagerHTML                                       = new ImageManagerHTML();
    $oImageManagerHTML->bMultipleFileUpload                  = true;
    $oImageManagerHTML->aMultipleFileUploadAllowedExtensions = ['png', 'jpg', 'gif', 'jpeg'];
    $oImageManagerHTML->aImages                              = $oPage->getImages('all');
    $oImageManagerHTML->cropLink                             = ADMIN_FOLDER . '/' . http_get('controller') . '/crop-image';
    $oImageManagerHTML->sUploadUrl                           = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId;
    $oImageManagerHTML->aNeededImageFileReferences           = TemplateSettings::get('pages', 'images/neededImageFiles');
    $oImageManagerHTML->bShowCropAfterUploadOption           = false;
    $oImageManagerHTML->iContainerIDAddition                 = '1';
    $oImageManagerHTML->sExtraUploadLine                     = sysTranslations::get('global_image_size_960_700');

    $oImageManagerHTML->sExtraUploadedLine = '';
    foreach ($oPage->getLocales() as $oLocale) {
        $oImageManagerHTML->sExtraUploadedLine .= sysTranslations::get('global_images_diplayed') . ' <a href="' . $oPage->getBaseUrlPath($oLocale) . '" target="_blank">' . $oPage->getShortTitle() . '</a>';
    }

    // set settings for file management
    $oFileManagerHTML                      = new FileManagerHTML();
    $oFileManagerHTML->aFiles              = $oPage->getFiles('all');
    $oFileManagerHTML->sUploadUrl          = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId;
    $oFileManagerHTML->bMultipleFileUpload = true;
    $oFileManagerHTML->bTitleRequired      = false;

    // set link manager
    $oLinkManagerHTML             = new LinkManagerHTML();
    $oLinkManagerHTML->aLinks     = $oPage->getLinks('all');
    $oLinkManagerHTML->sUploadUrl = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId;

    // set video manager
    $oVideoLinkManagerHTML                = new VideoLinkManagerHTML();
    $oVideoLinkManagerHTML->aVideoLinks = $oPage->getVideoLinks('all');
    $oVideoLinkManagerHTML->sUploadUrl    = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId;

    ##########################
    # START AUTOCOMPLETE NEWS#
    ##########################

    $aAutocompleters           = [];
    $aAutocompletersToRegister = [];
    if (moduleExists('newsItems')) {
        $aAutocompletersToRegister[] = [
            'title'       => sysTranslations::get('autocomplete_news_items_title'),
            'masterModel' => Page::class,
            'masterId'    => $oPage->pageId,
            'slaveModel'  => NewsItem::class,
        ];
    }
    if (moduleExists('usps')) {
        $aAutocompletersToRegister[] = [
            'title'       => sysTranslations::get('autocomplete_usps_title'),
            'masterModel' => Page::class,
            'masterId'    => $oPage->pageId,
            'slaveModel'  => Usp::class,
        ];
    }

    foreach ($aAutocompletersToRegister as $aSetting) {
        array_push($aAutocompleters, AutocompleteManager::create($aSetting));
    }
    #######################
    #END AUTOCOMPLETE NEWS#
    #######################

    $oPageLayout->sViewPath = getAdminView('page_form', 'pages');
} elseif (http_get("param1") == 'crop-image' && is_numeric(http_get("imageId"))) {
    $oImage = ImageManager::getImageById(http_get("imageId"));

    if (!$oImage) {
        $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_available'); //error getting image
        http_redirect(http_session('cropReferrer'));
    }

    $aImageSettings = TemplateSettings::get('pages', 'images');
    $sReferrer      = http_session('cropReferrer');
    $sReferrerText  = 'pagina bewerken';
    $aCropSettings  = ImageManager::handleImageCropSettings($oImage, $aImageSettings, $sReferrer, $sReferrerText);

    // add setting to session in an array
    $_SESSION['aCropSettings'] = $aCropSettings;

    http_redirect(ADMIN_FOLDER . '/crop');
} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if (CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oPage = PageManager::getPageById(http_get("param2"));
        }

        if ($oPage && PageManager::deletePage($oPage)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('pages_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('pages_not_deleted'); //save status update into session
        }
    }

    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get("param1") == 'structuur-wijzigen') {

    if (http_post("action") == 'savePageStructure' && CSRFSynchronizerToken::validate()) {

        #
        if (http_post("pageStructure")) {
            parse_str(http_post("pageStructure"), $aPageArray);
        }

        $iPageOrder = 0;
        // loop trough all pages and save new parentId
        foreach ($aPageArray['page'] AS $iPageId => $mParentPageReference) {

            // get page and save
            $oPage = PageManager::getPageById($iPageId);

            if (!$oPage) {
                continue;
            }
            if ($mParentPageReference === 'root') {
                $oPage->parentPageId = null;
            } elseif (is_numeric($mParentPageReference)) {
                $oPage->parentPageId = $mParentPageReference;
            } else {
                continue;
            }

            $oPage->order = $iPageOrder; // set the order to the page
            // save page
            if ($oPage->isValid()) {
                PageManager::savePage($oPage);
            }
            $iPageOrder++;
        }
        $_SESSION['statusUpdate'] = 'Paginastructuur is opgeslagen'; //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    $aAllLevel1Pages = PageManager::getPagesByFilter(['showAll' => true, 'level' => 1, 'languageId' => AdminLocales::language()]);

    $oPageLayout->sViewPath = getAdminView('pages_change_structure', 'pages');
} elseif (http_get("param1") == 'ajax-setOnline') {
    if (!CSRFSynchronizerToken::validate()) {
        die(json_encode(['success'=>false]));
    }
    $bOnline = http_get("online", 0); //no value, set offline by default
    $bAjax   = http_get("ajax", false); //controller requested by ajax
    $iPageId = http_get("param2");
    $oPage   = PageManager::getPageById($iPageId);
    $oResObj = new stdClass(); //standard class for json feedback
    // update online for page
    if ($oPage && $oPage->isOnlineChangeable()) {
        $oResObj->success = PageManager::updateOnlineByPage($bOnline, $oPage);
        $oResObj->pageId  = $iPageId;
        $oResObj->online  = $bOnline;
    }

    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }
    print json_encode($oResObj);
    die;
} elseif (http_get('param1') == 'image-list') {
    $oPage = PageManager::getPageById(http_get('param2'));

    if (!$oPage) {
        die;
    }

    $aImages     = $oPage->getImages('all');
    $aImageFiles = [];
    $aLinks      = [];
    foreach ($aImages AS $oImage) {
        $oImageFile = $oImage->getImageFileByReference('detail');
        if (!$oImageFile) {
            continue;
        }
        $oLink        = new stdClass();
        $oLink->title = $oImageFile->title ? $oImageFile->title : $oImageFile->name;
        $oLink->value = $oImageFile->link;
        $aLinks[]     = $oLink;
    }

    die(json_encode($aLinks));
} elseif (http_get('param1') == 'button-dialog') {

    $aPages = PageManager::getPagesByFilter(['showAll' => 1, 'level' => 1, 'online' => 1, 'languageId' => AdminLocales::language()]); // all online level 1 pages
    $aLinks = [];

    function makeListTree($aPages, &$aLinks)
    {
        foreach ($aPages AS $oPage) {
            $sLeadingChars = '';
            for ($iC = $oPage->level; $iC > 1; $iC--) {
                $sLeadingChars .= '--';
            }
            $oLink        = new stdClass();
            $oLink->text  = htmlspecialchars($sLeadingChars . $oPage->getShortTitle());
            $oLink->value = getBaseUrl(AdminLocales::getAdminLocale()) . $oPage->getUrlPath();
            $aLinks[]     = $oLink;
            makeListTree($oPage->getSubPages('online-all'), $aLinks); //call function recursive
        }
    }

    makeListTree($aPages, $aLinks);
    die(json_encode($aLinks));

} elseif (http_get('param1') == 'link-list') {
    $aPages = PageManager::getPagesByFilter(['showAll' => 1, 'level' => 1, 'online' => 1, 'languageId' => AdminLocales::language()]); // all online level 1 pages

    $aLinks = [];

    function makeListTree($aPages, &$aLinks)
    {
        foreach ($aPages AS $oPage) {
            $sLeadingChars = '';
            for ($iC = $oPage->level; $iC > 1; $iC--) {
                $sLeadingChars .= '--';
            }
            $oLink        = new stdClass();
            $oLink->title = $sLeadingChars . $oPage->getShortTitle();
            $oLink->value = getBaseUrl(AdminLocales::getAdminLocale()) . $oPage->getUrlPath();
            $aLinks[]     = $oLink;
            makeListTree($oPage->getSubPages('online-all'), $aLinks); //call function recursive
        }
    }

    makeListTree($aPages, $aLinks);

    die(json_encode($aLinks));
} elseif (http_get('param1') == 'ajax-checkName') {

    # check if name exists
    $oPage = PageManager::getPageByName(http_get('name'), AdminLocales::language());
    if ($oPage && $oPage->pageId != http_get('pageId')) {
        echo 'false';
    } else {
        echo 'true';
    }
    die;
} elseif (http_get('param1') == 'copy-structure') {

    $aAllLevel1Pages = PageManager::getPagesByFilter(['showAll' => 1, 'level' => 1, 'languageId' => AdminLocales::language()]);

    if (http_post('action') == 'copyStructure' && CSRFSynchronizerToken::validate()) {

        if (!empty(http_post('languageId'))) {

            function makeListTree($aPages, Page $oParent = null)
            {
                $oPage = new Page();
                foreach ($aPages AS $oPage) {
                    $oNewPage             = clone $oPage;
                    $oNewPage->pageId     = null;
                    $oNewPage->languageId = http_post('languageId');
                    if ($oParent) {
                        // set parent when set
                        $oNewPage->parentPageId = $oParent->pageId;
                    }

                    if ($oNewPage->isValid()) {
                        PageManager::savePage($oNewPage);
                        makeListTree($oPage->getSubPages('all'), $oNewPage); //call function recursive
                    }
                }
            }

            # start recursive displaying pages
            makeListTree($aAllLevel1Pages);
            $_SESSION['statusUpdate'] = sysTranslations::get('pages_structure_copied'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('pages_languageId_title'); //save status update into session
        }
    }

    $aLanguages = LanguageManager::getLanguagesByFilter(['hasLocale' => true]);
    foreach ($aLanguages AS $iKey => $oLanguage) {
        if (!empty(PageManager::getPagesByFilter(['languageId' => $oLanguage->languageId]))) {
            unset($aLanguages[$iKey]);
        }
    }

    $oPageLayout->sViewPath = getAdminView('pages_copy_structure', 'pages');
} else {

    #display page overview
    $aAllLevel1Pages = PageManager::getPagesByFilter(['showAll' => 1, 'level' => 1, 'languageId' => AdminLocales::language()]);

    $oPageLayout->sViewPath = getAdminView('pages_overview', 'pages');
}

// include default template
include_once getAdminView('layout');
