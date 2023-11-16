<?php

$aUrlParts = parse_url(LIVE_HTTP_URL);
if (!empty($aUrlParts['host']) && isset($_SERVER['HTTP_HOST']) && $aUrlParts['host'] == $_SERVER['HTTP_HOST']) {
    Debug::logError("", "LIVE_HTTP_URL same as HTTP_HOST", __FILE__, __LINE__, DEVELOPER_NAME . " created a infinite loop, please change the LIVE_HTTP_URL to the right URL or set to empty string", Debug::LOG_IN_EMAIL);
    showHttpError(404);
} else {

    $aLiveUrlParts    = parse_url(LIVE_HTTP_URL);
    $aCurrentUrlParts = parse_url(getCurrentUrl());

    // check if current host is same as live http host
    if (!empty($aLiveUrlParts['host']) && !empty($aCurrentUrlParts['host']) && strtolower($aLiveUrlParts['host']) === strtolower($aCurrentUrlParts['host'])) {
        Debug::logError('0', 'LIVE_HTTP_URL equals current URL', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
        showHttpError(404);
    }

    // check if file exists on remote
    if (LIVE_HTTP_URL != '' && LIVE_HTTP_URL != 'x') {
        $sFileContents = @file_get_contents(LIVE_HTTP_URL . '/' . http_get('file'));
    } else {
        $sFileContents = false;
    }

    if ($sFileContents === false && preg_match('#(.*)/(autoresized_(?:w(\d+))?(?:h(\d+))?)/(.*)#', http_get('file'), $aMatches)) {
        // file not found, but is image resize
        $iW                 = !empty($aMatches[3]) ? $aMatches[3] : null;
        $iH                 = !empty($aMatches[4]) ? $aMatches[4] : null;
        $sOriginalFolder    = $aMatches[1];
        $sAutoresizedFolder = $aMatches[2];
        $sFileName          = $aMatches[5];
        $sOriginalLocation  = $sOriginalFolder . '/' . $sFileName;
        $bRemoveTmpFile     = false;
        if (!file_exists(DOCUMENT_ROOT . '/' . $sOriginalLocation)) {
            // file not found on local machine, try to find on remote
            if (LIVE_HTTP_URL != '' && LIVE_HTTP_URL != 'x') {
                $sFileContentsOriginal = @file_get_contents(LIVE_HTTP_URL . '/' . $sOriginalLocation);
            } else {
                $sFileContentsOriginal = false;
            }

            if ($sFileContentsOriginal !== false) {
                // file downloaded, put in temp folder to use for autoresize
                if (!file_exists(DOCUMENT_ROOT . '/private_files/tmp/autoresize')) {
                    // folder does not exist but parent folder is writable so create it
                    if (!is_writable(DOCUMENT_ROOT . '/private_files') || !mkdir(DOCUMENT_ROOT . '/private_files/tmp/autoresize', 0777, true)) {
                        showHttpError(404);
                    }
                }

                // original location is location where tmp file is saved
                $sOriginalLocation = 'private_files/tmp/autoresize/' . $sFileName;
                // destination location is same as original because it's all temporary
                $sDestinationLocation = $sOriginalLocation;
                $bRemoveTmpFile       = true; // unlink file when finished
                if (!file_put_contents($sOriginalLocation, $sFileContentsOriginal)) {
                    showHttpError(404);
                }
            }
        } else {
            // file found on local file system, autoresize will be saved locally
            if (!file_exists($sOriginalFolder . '/' . $sAutoresizedFolder)) {
                // folder does not exist but parent folder is writable so create it
                if (!is_writable($sOriginalFolder) || !mkdir($sOriginalFolder . '/' . $sAutoresizedFolder, 0777, true)) {
                    showHttpError(404);
                }
            }

            // set destination to requested file name and location on local file system
            $sDestinationLocation = http_get('file');
        }

        if (!empty($sOriginalLocation) && !empty($sDestinationLocation)) {
            // original found on local so create resize on local
            if (!empty($iW) && !empty($iH)) {
                ImageManager::autoCropAndResizeImage(DOCUMENT_ROOT . '/' . $sOriginalLocation, DOCUMENT_ROOT . '/' . $sDestinationLocation, $iW, $iH, $sErrorMsg);
            } elseif (!empty($iW)) {
                ImageManager::resizeImageW(DOCUMENT_ROOT . '/' . $sOriginalLocation, DOCUMENT_ROOT . '/' . $sDestinationLocation, $iW, $sErrorMsg);
            } elseif (!empty($iH)) {
                ImageManager::resizeImageH(DOCUMENT_ROOT . '/' . $sOriginalLocation, DOCUMENT_ROOT . '/' . $sDestinationLocation, $iH, $sErrorMsg);
            }

            // get file contents, next time the file will be returned directly by the server
            $sFileContents = @file_get_contents(DOCUMENT_ROOT . '/' . $sDestinationLocation);
            if ($bRemoveTmpFile) {
                // remove destination file if it is a temporary file
                @unlink(DOCUMENT_ROOT . '/' . $sDestinationLocation);
            }
        }
    }

    if ($sFileContents !== false) {

        // get mime header
        $oFinfo    = new finfo(FILEINFO_MIME_TYPE);
        $sMimeType = $oFinfo->buffer($sFileContents);

        // set mimeheader and print contents
        header('Content-Type: ' . $sMimeType);
        die($sFileContents);
    } else {
        showHttpError(404);
    }
}