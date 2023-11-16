<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

# Controller is only used for ajax and redirecting

if (http_get("param1") == 'ajax-delete') {
    if (!CSRFSynchronizerToken::validate()) {
        die(json_encode(['success'=>false]));
    }


    $oResObj          = new stdClass();
    $oResObj->success = false;

    $iMediaId = Request::postVar('mediaId');
    if (is_numeric($iMediaId)) {
        $oVideoLink = VideoLinkManager::getVideoLinkById($iMediaId);
        if ($oVideoLink && $oVideoLink->isDeletable() === true) {
            if (VideoLinkManager::deleteVideoLink($oVideoLink)) {
                $oResObj->mediaId = $iMediaId;
                $oResObj->success = true;
            }
        }
    }

    print json_encode($oResObj);
} elseif (http_get("param1") == 'ajax-setOnline') {
    if (!CSRFSynchronizerToken::validate()) {
        die(json_encode(['success'=>false]));
    }

    $oResObj          = new stdClass();
    $oResObj->success = false;

    $iMediaId = Request::postVar('mediaId');
    $iOnline  = Request::postVar('online');
    if (is_numeric($iMediaId)) {
        $oVideoLink = VideoLinkManager::getVideoLinkById($iMediaId);
        if ($oVideoLink && $oVideoLink->isOnlineChangeable()) {
            $aData = [
                'mediaId' => $iMediaId,
                'title'   => $oVideoLink->title,
                'link'    => $oVideoLink->link,
                'online'  => $iOnline,
            ];

            VideoLinkManager::saveVideoLink($aData, true);
            $oResObj->mediaId = $iMediaId;
            $oResObj->online  = ($iOnline ? 0 : 1);
            $oResObj->success = true;
        }
    }

    print json_encode($oResObj);
} elseif (http_get("param1") == 'ajax-edit') {
    if (!CSRFSynchronizerToken::validate()) {
        die(json_encode(['success'=>false]));
    }

    $oResObj          = new stdClass();
    $oResObj->success = false;
    $iMediaId         = Request::postVar('mediaId');
    $sTitle           = Request::postVar('title');
    $sLink            = Request::postVar('link');
    if (is_numeric($iMediaId)) {
        $oVideoLink        = VideoLinkManager::getVideoLinkById($iMediaId);

        if ($oVideoLink && $oVideoLink->isEditable() === true) {
            $oVideoLink->title = $sTitle;
            $oVideoLink->link  = $sLink;
            $aData = [
                'mediaId' => $iMediaId,
                'title'   => $sTitle,
                'link'    => $sLink,
            ];
            VideoLinkManager::saveVideoLink($aData);
            $oResObj->mediaId   = $iMediaId;
            $oResObj->title     = $oVideoLink->title;
            $oResObj->link      = $oVideoLink->link;
            $oResObj->embedLink = $oVideoLink->getEmbedLink(false);
            $oResObj->success   = true;
        }
    }

    print json_encode($oResObj);
} elseif (http_get("param1") == 'ajax-saveOrder') {
    if (!CSRFSynchronizerToken::validate()) {
        die(json_encode(['success'=>false]));
    }

    $oResObj          = new stdClass();
    $oResObj->success = false;

    $sMediaIds = Request::postVar('mediaIds'); // get media ids komma seperated
    $aMediaIds = explode(",", $sMediaIds); // explode ids to array
    # update order
    VideoLinkManager::updateVideoLinkOrder($aMediaIds);
    $oResObj->success = true;

    print json_encode($oResObj);
}
