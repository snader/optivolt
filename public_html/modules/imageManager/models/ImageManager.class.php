<?php

class ImageManager
{

    const POS_LEFT_TOP      = 1;
    const POS_CENTER_TOP    = 2;
    const POS_RIGHT_TOP     = 3;
    const POS_LEFT_MIDDLE   = 4;
    const POS_CENTER_MIDDLE = 5;
    const POS_RIGHT_MIDDLE  = 6;
    const POS_LEFT_BOTTOM   = 7;
    const POS_CENTER_BOTTOM = 8;
    const POS_RIGHT_BOTTOM  = 9;

    /**
     * save Image object
     *
     * @param Image $oImage
     */
    public static function saveImage(Image $oImage)
    {

        $oDb = DBConnections::get();

        # new image
        $sQuery = ' INSERT INTO
                        `images`
                        (
                            `imageId`,
                            `order`,
                            `online`
                        )
                        VALUES (
                            ' . db_int($oImage->imageId) . ',
                            ' . db_int($oImage->order) . ',
                            ' . db_int($oImage->online) . '
                        )
                        ON DUPLICATE KEY UPDATE
                            `imageId`=VALUES(`imageId`),
                            `order`=VALUES(`order`),
                            `online`=VALUES(`online`)
                    ;';

        $oDb->query($sQuery, QRY_NORESULT);
        if ($oImage->imageId === null) {
            $oImage->imageId = $oDb->insert_id;
        }

        # save image files
        self::saveImageFiles($oImage);
    }

    /**
     * save image files from image
     *
     * @param Image $oImage
     */
    private static function saveImageFiles(Image $oImage)
    {

        $sFileValues      = '';
        $sImageFileValues = '';

        $oDb = DBConnections::get();
        foreach ($oImage->getImageFiles() as $oImageFile) {

            [
                $iW,
                $iH,
                $iImageType,
                $sImageSizeAttr,
            ] = getimagesize(DOCUMENT_ROOT . $oImageFile->getLinkWithoutQueryString());
            $oImageFile->imageSizeAttr = $sImageSizeAttr;

            # continue
            if ($oImageFile->mediaId !== null) {
                continue;
            }

            # save imageFile
            $sQuery = ' INSERT INTO
                            `media`
                        (
                            `mediaId`,
                            `link`,
                            `title`,
                            `type`,
                            `online`,
                            `order`,
                            `created`
                        )
                        VALUES
                        (
                            ' . db_int($oImageFile->mediaId) . ',
                            ' . db_str($oImageFile->link) . ',
                            ' . db_str($oImageFile->title) . ',
                            ' . db_str($oImageFile->type) . ',
                            ' . db_int($oImageFile->online) . ',
                            ' . db_int($oImageFile->order) . ',
                            NOW()
                        )ON DUPLICATE KEY UPDATE
                            `title` = VALUES(`title`),
                            `online` = VALUES(`online`),
                            `order` = VALUES(`order`)
                        ;';

            $oDb->query($sQuery, QRY_NORESULT);
            $oImageFile->mediaId = $oDb->insert_id;
            $oImageFile->imageId = $oImage->imageId;

            $sFileValues      .= ($sFileValues == '' ? '' : ',') . '(' . db_int($oImageFile->mediaId) . ',' . db_str($oImageFile->name) . ',' . db_str($oImageFile->mimeType) . ',' . db_int($oImageFile->size) . ')';
            $sImageFileValues .= ($sImageFileValues == '' ? '' : ',') . '(' . db_int($oImageFile->imageId) . ',' . db_int($oImageFile->mediaId) . ',' . db_str($oImageFile->reference) . ',' . db_str($oImageFile->imageSizeAttr) . ')';
        }

        # insert file values
        if ($sFileValues != '') {
            $sQuery = ' INSERT INTO
                            `files`
                        (
                            `mediaId`,
                            `name`,
                            `mimeType`,
                            `size`
                        )
                        VALUES
                        ' . $sFileValues . '
                        ON DUPLICATE KEY UPDATE
                            `size`=VALUES(`size`)
                        ;';
            $oDb->query($sQuery, QRY_NORESULT);
        }

        # insert imageFile values
        if ($sImageFileValues != '') {
            $sQuery = ' INSERT INTO
                            `image_files`
                        (
                            `imageId`,
                            `mediaId`,
                            `reference`,
                            `imageSizeAttr`
                        )
                        VALUES
                        ' . $sImageFileValues . '
                        ON DUPLICATE KEY UPDATE
                            `imageSizeAttr`=VALUES(`imageSizeAttr`)
                        ;';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    /**
     * save a single imageFile
     *
     * @param ImageFile $oImageFile
     */
    public static function saveImageFile(ImageFile $oImageFile)
    {

        [
            $iW,
            $iH,
            $iImageType,
            $sImageSizeAttr,
        ] = getimagesize(DOCUMENT_ROOT . $oImageFile->getLinkWithoutQueryString());
        $oImageFile->imageSizeAttr = $sImageSizeAttr;

        $oDb = DBConnections::get();
        # save imageFile
        # insert media, can not be updated because of special functions
        $sQuery = ' INSERT INTO
                        `media`
                    (
                        `mediaId`,
                        `link`,
                        `title`,
                        `type`,
                        `online`,
                        `order`,
                        `created`,
                        `modified`
                    )
                    VALUES
                    (
                        ' . db_int($oImageFile->mediaId) . ',
                        ' . db_str($oImageFile->getLinkWithoutQueryString()) . ',
                        ' . db_str($oImageFile->title) . ',
                        ' . db_str($oImageFile->type) . ',
                        ' . db_int($oImageFile->online) . ',
                        ' . db_int($oImageFile->order) . ',
                        NOW(),
                        NOW()
                    )ON DUPLICATE KEY UPDATE
                            `title` = VALUES(`title`),
                            `online` = VALUES(`online`),
                            `order` = VALUES(`order`),
                            `modified` = VALUES(`modified`)
                        ;';
        $oDb->query($sQuery, QRY_NORESULT);
        if ($oImageFile->mediaId === null) {
            $oImageFile->mediaId = $oDb->insert_id;
        }

        # insert file (only update size on duplicate key)
        $sQuery = ' INSERT INTO
                        `files`
                    (
                        `mediaId`,
                        `name`,
                        `mimeType`,
                        `size`
                    )
                    VALUES(
                        ' . db_int($oImageFile->mediaId) . ',
                        ' . db_str($oImageFile->name) . ',
                        ' . db_str($oImageFile->mimeType) . ',
                        ' . db_int($oImageFile->size) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `size`=VALUES(`size`)
                        ;';
        $oDb->query($sQuery, QRY_NORESULT);

        # insert imageFile only if not exists
        $sQuery = ' INSERT INTO
                        `image_files`
                    (
                        `imageId`,
                        `mediaId`,
                        `reference`,
                        `imageSizeAttr`
                    )
                    VALUES
                    (
                        ' . db_str($oImageFile->imageId) . ',
                        ' . db_str($oImageFile->mediaId) . ',
                        ' . db_str($oImageFile->reference) . ',
                        ' . db_str($oImageFile->imageSizeAttr) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `imageSizeAttr`=VALUES(`imageSizeAttr`)
                    ;';
        $oDb->query($sQuery, QRY_NORESULT);

        // update to existing ImageFile so reset resized versions
        if (!empty($oImageFile->mediaId)) {
            // delete all auto resized versions of this ImageFile
            $oImageFile->deleteAutoResized();
        }
    }

    /**
     * get all image files for a image
     *
     * @param int $iImageId
     */
    public static function getImageFilesByImageId($iImageId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `image_files` AS `if`
                    JOIN
                        `files` AS `f` USING(`mediaId`)
                    JOIN
                        `media` AS `m` USING(`mediaId`)
                    WHERE
                        `imageId` = ' . db_int($iImageId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'ImageFile');
    }

    /**
     * get an image by imageId
     *
     * @param int  $iImageId
     * @param bool $bShowAll
     *
     * @return Image
     */
    public static function getImageById($iImageId, $bShowAll = true)
    {
        $sWhere = 'WHERE `i`.`imageId` = ' . db_int($iImageId) . '';

        if (!$bShowAll) {
            $sWhere .= ' AND `i`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `i`.*
                    FROM
                        `images` AS `i`
                    ' . $sWhere . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'Image');
    }

    /**
     * get Image file object by reference
     *
     * @param int    $iImageId
     * @param string $sReference
     * @param ImageFile
     */
    public static function getImageFileByImageAndReference($iImageId, $sReference)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `image_files` AS `if`
                    JOIN
                        `files` AS `f` USING(`mediaId`)
                    JOIN
                        `media` AS `m` USING(`mediaId`)
                    WHERE
                        `imageId` = ' . db_int($iImageId) . '
                    AND
                        `if`.`reference` = ' . db_str($sReference) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'ImageFile');
    }

    /**
     * get Image file object by link
     *
     * @param string $sLink
     *
     * @return ImageFile
     */
    public static function getImageFileByLink($sLink)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `image_files` AS `if`
                    JOIN
                        `files` AS `f` USING(`mediaId`)
                    JOIN
                        `media` AS `m` USING(`mediaId`)
                    WHERE
                        `m`.`link` = ' . db_str($sLink) . '
                    LIMIT 1
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'ImageFile');
    }

    /**
     *
     * @param int $iOriW
     * @param int $iOriH
     * @param int $iW
     * @param int $iH
     * @param int $newW
     * @param int $newH
     */
    private static function getResizeDimensions($iOriW, $iOriH, $iW = null, $iH = null, &$iNewW = 0, &$iNewH = 0)
    {
        if ($iW && $iH) {

            #set defaults
            $iH1 = 0;
            $iW1 = 0;
            $iH2 = 0;
            $iW2 = 0;

            #resize based on height
            if ($iOriH > $iH) {
                $iNewH = $iH;
                $iNewW = round($iNewH * $iOriW / $iOriH);
                if ($iNewW <= $iW && $iNewH <= $iH) {
                    $iW1 = $iNewW;
                    $iH1 = $iNewH;
                }
            }

            #resize based on width
            if ($iOriW > $iW) {
                $iNewW = $iW;
                $iNewH = round($iNewW * $iOriH / $iOriW);
                if ($iNewW <= $iW && $iNewH <= $iH) {
                    $iW2 = $iNewW;
                    $iH2 = $iNewH;
                }
            }

            # check which option results in a bigger area
            if (($iW1 * $iH1) > ($iW2 * $iH2)) {
                $iNewW = $iW1;
                $iNewH = $iH1;
            } else {
                $iNewW = $iW2;
                $iNewH = $iH2;
            }

            # already right size
            if ($iOriH <= $iH && $iOriW <= $iW) {
                $iNewW = $iOriW;
                $iNewH = $iOriH;
            }
        } elseif ($iW) {
            # only fit in width
            if ($iOriW > $iW) {
                $iNewW = $iW;
                $iNewH = round($iNewW * $iOriH / $iOriW);
            } else {
                $iNewW = $iOriW;
                $iNewH = $iOriH;
            }
        } elseif ($iH) {
            # only fit in height
            if ($iOriH > $iH) {
                $iNewH = $iH;
                $iNewW = round($iNewH * $iOriW / $iOriH);
            } else {
                $iNewW = $iOriW;
                $iNewH = $iOriH;
            }
        } else {
            # no resizing is needed
            $iNewW = $iOriW;
            $iNewH = $iOriH;
        }
    }

    /**
     *
     * resize an image, save the file to the server
     * save a new ImageFile to the database
     *
     * @param string $sOriLocation  location of original, local or remote
     * @param string $sDestination  destination location (optional - overwrite original)
     * @param int    $iW            width of the image to become
     * @param int    $iH            height of the image to become
     * @param int    $iCx           crop start position x default: 0
     * @param int    $iCy           crop start position y default: 0
     * @param int    $iCw           crop box width
     * @param int    $iHc           crop box height
     * @param int    $iJpegQuality  queality of the jpeg file default: -1 (setts to 93 by default for GD or default webservice value)
     * @param string $sErrorMsg     variable for error messaging
     * @param bool   $bAbsoluteSize take size absolute (no resizing, just take size)
     *
     * @return bool
     */
    private static function resizeAndCrop($sOriLocation, $sDestination = null, $iW = null, $iH = null, $iCx = 0, $iCy = 0, $iCw = null, $iCh = null, $iJpegQuality = -1, &$sErrorMsg = null, $bAbsoluteSize = false)
    {

        # file exists?
        if (!file_exists($sOriLocation)) {
            $sErrorMsg = 'Het bestand kan niet worden gevonden';

            return false;
        }

        # get image properties
        $aFileProps = getimagesize($sOriLocation);

        $iOriW        = $aFileProps[0];
        $iOriH        = $aFileProps[1];
        $iOriType     = $aFileProps[2];
        $iOriMimeType = $aFileProps['mime'];

        # set destination to original if not given
        if ($sDestination === null) {
            $sDestination = $sOriLocation;
        }

        ## Check if resize + if resize is needed in the first place.
        if (empty($iCx) && empty($iCy) && empty($iCw) && empty($iCh)) {

            ## We established there is only a resize, now check if it's only W, only H or both and check the sizes.
            ## If the required sizes are smaller than the original AND no comprimise is needed ($iJpegQuality == -1), we will just copy the image to the new location.
            if (
            (
                (!empty($iW) && !empty($iH) && $iOriW <= $iW && $iOriH <= $iH) ||
                (!empty($iW) && empty($iH) && $iOriW <= $iW) ||
                (empty($iW) && !empty($iH) && $iOriH <= $iH)
            )
            ) {
                ## Don't resize, just copy
                $bSkipCopy = false;
                if ($sOriLocation == $sDestination) {
                    $bSkipCopy = true;
                }
                if (!$bSkipCopy && !copy($sOriLocation, $sDestination)) {
                    return false;
                } else {
                    if ($iJpegQuality == 100) {
                        return true;
                    }

                    if ($iOriType == IMAGETYPE_JPEG || $iOriType == IMAGETYPE_PNG) {
                        ## Compress image
                        if (moduleExists('webservices')) {
                            $aSettings = [];
                            if ($iJpegQuality !== -1) {
                                $aSettings['compression'] = $iJpegQuality;
                            }

                            $aSettings['webp'] = true;
                            $aCompressedImages = ImageEditorWebserviceManager::getCompressedImages([basename($sDestination) => base64_encode(file_get_contents($sDestination))], $aSettings);
                            if (!empty($aCompressedImages['images'])) {
                                // overwrite file with compressed one
                                file_put_contents($sDestination, base64_decode(array_shift($aCompressedImages['images'])));
                            }
                            if (!empty($aCompressedImages['webp'])) {
                                file_put_contents($sDestination . '.webp', base64_decode(array_shift($aCompressedImages['webp'])));
                            }
                        }

                        return true;
                    }
                }
            }
        }

        # set absoulte size if needed
        if ($bAbsoluteSize === true) {
            # only W, calculate H (absolute W)
            if ($iW !== null && $iH === null) {
                $iNewW = $iW;
                $iNewH = $iNewW * $iOriH / $iOriW;
            } elseif ($iW === null && $iH !== null) {
                # only H, calculate W (absolute H)
                $iNewH = $iH;
                $iNewW = $iNewH * $iOriW / $iOriH;
            } else {
                # make destination image exact size
                $iNewW = $iW;
                $iNewH = $iH;
            }
        } else {
            self::getResizeDimensions($iOriW, $iOriH, $iW, $iH, $iNewW, $iNewH);
        }

        # set crop box size to full original if one dimension is missing (resize)
        if ($iCw === null || $iCh === null) {
            $iCw = $iOriW;
            $iCh = $iOriH;
        } else {
            # cropbox sizes are given (crop)
            if ($bAbsoluteSize === true) {
                # destination filesize equals crop box size
                $iNewW = $iW;
                $iNewH = $iH;
            } else {
                # resize destination filedimensions by based on cropbox size (new cropped image)
                self::getResizeDimensions($iCw, $iCh, $iW, $iH, $iNewW, $iNewH);
            }
        }

        # copy image, resize and save
        switch ($iOriType) {
            case IMAGETYPE_JPEG:
                $rOriginalImage  = imagecreatefromjpeg($sOriLocation);
                $rTemporaryImage = imagecreatetruecolor($iNewW, $iNewH);

                imagecopyresampled($rTemporaryImage, $rOriginalImage, 0, 0, $iCx, $iCy, $iNewW, $iNewH, $iCw, $iCh);

                // compress image
                if (moduleExists('webservices') && $iJpegQuality < 100) {
                    $bSuccess  = imagejpeg($rTemporaryImage, $sDestination, 100);
                    $aSettings = [];
                    if ($iJpegQuality !== -1) {
                        $aSettings['compression'] = $iJpegQuality;
                    }
                    $aSettings['webp'] = true;
                    $aCompressedImages = ImageEditorWebserviceManager::getCompressedImages([basename($sDestination) => base64_encode(file_get_contents($sDestination))], $aSettings);
                    if (!empty($aCompressedImages['images'])) {
                        // overwrite file with compressed one
                        file_put_contents($sDestination, base64_decode(array_shift($aCompressedImages['images'])));
                    }
                    if (!empty($aCompressedImages['webp'])) {
                        file_put_contents($sDestination . '.webp', base64_decode(array_shift($aCompressedImages['webp'])));
                    }

                } else {
                    // do normal creating of image and set jpeg quality to 93 when not set
                    $bSuccess = imagejpeg($rTemporaryImage, $sDestination, $iJpegQuality !== -1 ? $iJpegQuality : 93);
                }

                return $bSuccess;
            case IMAGETYPE_GIF:
                $rOriginalImage  = imagecreatefromgif($sOriLocation);
                $rTemporaryImage = imagecreatetruecolor($iNewW, $iNewH);

                # transparency magic
                $iTransparency = imagecolortransparent($rOriginalImage);
                $iPalletSize   = imagecolorstotal($rOriginalImage);
                if ($iTransparency >= 0 && $iTransparency < $iPalletSize) {
                    $aTransparencyColor = imagecolorsforindex($rOriginalImage, $iTransparency);
                    $iTransparency      = imagecolorallocate($rTemporaryImage, $aTransparencyColor['red'], $aTransparencyColor['green'], $aTransparencyColor['blue']);
                    imagefill($rTemporaryImage, 0, 0, $iTransparency);
                    imagecolortransparent($rTemporaryImage, $iTransparency);
                }

                imagecopyresampled($rTemporaryImage, $rOriginalImage, 0, 0, $iCx, $iCy, $iNewW, $iNewH, $iCw, $iCh);
                $bSuccess = imagegif($rTemporaryImage, $sDestination);

                return $bSuccess;
            case IMAGETYPE_PNG:
                $rOriginalImage  = imagecreatefrompng($sOriLocation);
                $rTemporaryImage = imagecreatetruecolor($iNewW, $iNewH);

                /* transparantie magie */
                $iTransparency = imagecolortransparent($rOriginalImage);
                if ($iTransparency >= 0) {
                    # If we have a specific transparent color
                    $aTransparencyColor = imagecolorsforindex($rOriginalImage, $iTransparency);

                    # Get the original image's transparent color's RGB values
                    $iTransparency = imagecolorallocate($rTemporaryImage, $aTransparencyColor['red'], $aTransparencyColor['green'], $aTransparencyColor['blue']);

                    # Completely fill the background of the new image with allocated color.
                    imagefill($rTemporaryImage, 0, 0, $iTransparency);

                    # Set the background color for new image to transparent
                    imagecolortransparent($rTemporaryImage, $iTransparency);
                } else {
                    # Always make a transparent background color for PNGs that don't have one allocated already
                    # Turn off transparency blending (temporarily)
                    imagealphablending($rTemporaryImage, false);

                    # Create a new transparent color for image
                    $color = imagecolorallocatealpha($rTemporaryImage, 0, 0, 0, 127);

                    # Completely fill the background of the new image with allocated color.
                    imagefill($rTemporaryImage, 0, 0, $color);

                    # Restore transparency blending
                    imagesavealpha($rTemporaryImage, true);
                }

                imagecopyresampled($rTemporaryImage, $rOriginalImage, 0, 0, $iCx, $iCy, $iNewW, $iNewH, $iCw, $iCh);
                $bSuccess = imagepng($rTemporaryImage, $sDestination, 0);

                // compress image
                if (moduleExists('webservices') && $iJpegQuality < 100) {
                    $aSettings         = [];
                    $aSettings['webp'] = true;
                    $aCompressedImages = ImageEditorWebserviceManager::getCompressedImages([basename($sDestination) => base64_encode(file_get_contents($sDestination))], $aSettings);
                    if (!empty($aCompressedImages['images'])) {
                        // overwrite file with compressed one
                        file_put_contents($sDestination, base64_decode(array_shift($aCompressedImages['images'])));
                    }
                    if (!empty($aCompressedImages['webp'])) {
                        file_put_contents($sDestination . '.webp', base64_decode(array_shift($aCompressedImages['webp'])));
                    }
                }

                return $bSuccess;
            default:
                $sErrorMsg = 'Geen geldig type `' . $iOriMimeType . '`: png, jpg of gif';

                return false;
        }
    }

    /**
     * resize image to fit specified height
     *
     * @param type   $sOriLocation
     * @param type   $sDestination (optional - overwrite original)
     * @param type   $iH
     * @param string $sErrorMsg    message if error occures (optional)
     * @param int    $iJpegQuality quality (optional) -1 by default (takes system or webservice default value)
     *
     * @return bool
     */
    public static function resizeImageH($sOriLocation, $sDestination, $iH, &$sErrorMsg = null, $iJpegQuality = -1)
    {
        return self::resizeAndCrop($sOriLocation, $sDestination, null, $iH, null, null, null, null, $iJpegQuality, $sErrorMsg);
    }

    /**
     * resize image to fit specified width
     *
     * @param string $sOriLocation
     * @param string $sDestination (optional - overwrite original)
     * @param int    $iW
     * @param string $sErrorMsg    message if error occures
     * @param int    $iJpegQuality quality (optional) -1 by default (takes system or webservice default value)
     *
     * @return bool
     */
    public static function resizeImageW($sOriLocation, $sDestination, $iW, &$sErrorMsg = null, $iJpegQuality = -1)
    {
        return self::resizeAndCrop($sOriLocation, $sDestination, $iW, null, null, null, null, null, $iJpegQuality, $sErrorMsg);
    }

    /**
     * resize image to fit specified width and height (fit box)
     *
     * @param string $sOriLocation
     * @param string $sDestination  (optional - overwrite original)
     * @param int    $iW
     * @param int    $iH
     * @param string $sErrorMsg     message if error occures
     * @param int    $bAbsoluteSize take width and height as leading, do not scale just resize
     * @param int    $iJpegQuality  quality (optional) -1 by default (takes system or webservice default value)
     *
     * @return bool
     */
    public static function resizeImage($sOriLocation, $sDestination, $iW, $iH, &$sErrorMsg = null, $bAbsoluteSize = false, $iJpegQuality = -1)
    {
        return self::resizeAndCrop($sOriLocation, $sDestination, $iW, $iH, null, null, null, null, $iJpegQuality, $sErrorMsg, $bAbsoluteSize);
    }

    /**
     * crop image
     *
     * @param string $sOriLocation
     * @param string $sDestination  (optional - overwrite original)
     * @param int    $iW            width of the crop
     * @param int    $iH            height of the crop
     * @param int    $iCx           x position of cropbox
     * @param int    $iCy           y position of cropbox
     * @param int    $iCw           width of cropbox
     * @param int    $iCh           height of cropbox
     * @param string $sErrorMsg     message if error occures
     * @param int    $bAbsoluteSize take width and height as leading, do not scale just resize
     * @param int    $iJpegQuality  quality (optional) -1 by default (takes system or webservice default value)
     *
     * @return bool
     */
    public static function cropImage($sOriLocation, $sDestination, $iW, $iH, $iCx = 0, $iCy = 0, $iCw = null, $iCh = null, &$sErrorMsg = '', $bAbsoluteSize = false, $iJpegQuality = -1)
    {
        return self::resizeAndCrop($sOriLocation, $sDestination, $iW, $iH, $iCx, $iCy, $iCw, $iCh, $iJpegQuality, $sErrorMsg, $bAbsoluteSize);
    }

    /**
     * autocrop and resize image
     *
     * @param string $sOriLocation
     * @param string $sDestination  (optional)
     * @param int    $iCw
     * @param int    $iCh
     * @param string $sErrorMsg     (optional)
     * @param int    $bAbsoluteSize take width and height as leading, do not scale just resize
     * @param int    $iJpegQuality  quality (optional) -1 by default (takes system or webservice default value)
     *
     * @return bool
     */
    public static function autoCropAndResizeImage($sOriLocation, $sDestination, $iCw, $iCh, &$sErrorMsg = '', $bAbsoluteSize = false, $iJpegQuality = -1)
    {

        # set dimensions for use after crop
        $iCwForResize = $iCw;
        $iChForResize = $iCh;

        # calculate crop box x,y w,h
        self::getAutoCropBoxSize($sOriLocation, ($iCw / $iCh), $iCx, $iCy, $iCw, $iCh);

        return self::resizeAndCrop($sOriLocation, $sDestination, $iCwForResize, $iChForResize, $iCx, $iCy, $iCw, $iCh, $iJpegQuality, $sErrorMsg, $bAbsoluteSize);
    }

    /**
     * get x,y w,h of biggest possible crop
     *
     * @param string $sOriLocation
     * @param int    $iCx
     * @param int    $iCy
     * @param int    $iCw
     * @param int    $iCh
     */
    public static function getAutoCropBoxSize($sOriLocation, $fRatio, &$iCx, &$iCy, &$iCw, &$iCh)
    {
        # get image dimensions
        [
            $iW,
            $iH,
        ] = getimagesize($sOriLocation);

        # ratio of original image
        $fOriRatio = $iW / $iH;

        # ratio of crop
        $fRatio;

        # calculate width and height
        if ($fOriRatio >= 1 && $fRatio >= 1 && $fOriRatio < $fRatio) {
            # both landscape
            $iCw = $iW;
            $iCh = $iCw * (1 / $fRatio);
        } elseif ($fOriRatio >= 1 && $fRatio >= 1 && $fOriRatio > $fRatio) {
            # both landscape
            $iCh = $iH;
            $iCw = $iCh * $fRatio;
        } elseif ($fOriRatio <= 1 && $fRatio <= 1 && $fOriRatio > $fRatio) {
            # both portret
            $iCh = $iH;
            $iCw = $iCh * $fRatio;
        } elseif ($fOriRatio <= 1 && $fRatio <= 1 && $fOriRatio < $fRatio) {
            # both portret
            $iCw = $iW;
            $iCh = $iCw * (1 / $fRatio);
        } elseif ($fOriRatio < 1 && $fRatio > 1) {
            # ori portret, crop landscape
            $iCw = $iW;
            $iCh = $iCw * (1 / $fRatio);
        } elseif ($fOriRatio > 1 && $fRatio < 1) {
            # crop portret, ori landscape
            $iCh = $iH;
            $iCw = $iCh * $fRatio;
        } elseif ($fOriRatio == $fRatio) {
            # both same ratio
            $iCh = $iH;
            $iCw = $iW;
        }

        $iCx = ($iW - $iCw) / 2;
        $iCy = ($iH - $iCh) / 2;
    }

    /**
     * delete an image
     *
     * @param Image $oImage
     *
     * @return boolean
     */
    public static function deleteImage(Image $oImage)
    {

        if ($oImage->isDeletable()) {
            $oDb = DBConnections::get();

            # delete all imageFiles
            foreach ($oImage->getImageFiles() as $oImageFile) {

                if (!empty($oImageFile->link)) {
                    @unlink(DOCUMENT_ROOT . $oImageFile->getLinkWithoutQueryString());

                    //Delete webp files
                    if (file_exists(DOCUMENT_ROOT . $oImageFile->getLinkWithoutQueryString() . '.webp')) {
                        @unlink(DOCUMENT_ROOT . $oImageFile->getLinkWithoutQueryString() . '.webp');
                    }
                }

                // delete all auto resized versions of this ImageFile
                $oImageFile->deleteAutoResized();

                $sQuery = 'DELETE FROM `media` WHERE `mediaId` = ' . db_int($oImageFile->mediaId);
                $oDb->query($sQuery, QRY_NORESULT);
            }

            $sQuery = 'DELETE FROM `images` WHERE `imageId` = ' . db_int($oImage->imageId);

            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online for all imageFiles by Image
     *
     * @param int $iOnline
     * @param int $iImageId
     */
    public static function updateImageFilesOnlineByImage($iOnline, $iImageId)
    {

        $sQuery = ' UPDATE
                        `media` AS `m`
                    JOIN
                        `image_files` AS `if` USING(`mediaId`)
                    SET
                        `m`.`online` = ' . db_int($iOnline) . '
                    WHERE
                        `if`.`imageId` = ' . db_int($iImageId) . '
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * update title for all imageFiles by Image
     *
     * @param str $sTitle
     * @param int $iImageId
     */
    public static function updateImageFilesTitleByImage($sTitle, $iImageId)
    {

        $sQuery = ' UPDATE
                        `media` AS `m`
                    JOIN
                        `image_files` AS `if` USING(`mediaId`)
                    SET
                        `m`.`title` = ' . db_str($sTitle) . '
                    WHERE
                        `if`.`imageId` = ' . db_int($iImageId) . '
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * update image order
     *
     * @param array $aImageIds
     */
    public static function updateImageOrder(array $aImageIds)
    {
        $iT = 1;
        foreach ($aImageIds AS $iImageId) {
            $sQuery = '
                        UPDATE
                            `images` as `i`
                        SET
                            `i`.`order` = ' . db_int($iT) . '
                        WHERE
                            `i`.`imageId` = ' . db_int($iImageId) . ';
                ';

            $oDb = DBConnections::get();
            $oDb->query($sQuery, QRY_NORESULT);
            ++$iT;
        }
    }

    /**
     * apply watermark on given image
     *
     * @param string $sOriLocation  location of original image
     * @param array  $aWatermarks   array with watermarks and the minimum image size to use on
     * @param string $iPosition     constant defined in this class
     * @param int    $iMarginX      margin at the left or right, depending on the position
     * @param int    $iMarginY      margin at the bottom or top, depending on the position
     * @param int    $iTransparency level of transparency, 0 is invisible, 100 is normal, only works with jpeg watermarks
     */
    public static function addWatermark($sOriLocation, array $aWatermarks, $iPosition = self::POS_CENTER_BOTTOM, $iMarginX = 20, $iMarginY = 20, $iTransparency = 100, &$sErrorMsg = '')
    {

        # file exists?
        if (!file_exists($sOriLocation)) {
            $sErrorMsg = 'Het bestand kan niet worden gevonden';

            return false;
        }

        # get image properties
        $aFileProps = getimagesize($sOriLocation);

        $iOriW        = $aFileProps[0];
        $iOriH        = $aFileProps[1];
        $iOriType     = $aFileProps[2];
        $iOriMimeType = $aFileProps['mime'];

        // greates min size at the top
        krsort($aWatermarks);

        // get right watermark for size
        foreach ($aWatermarks as $iMinSize => $sWatermarkLocation) {
            if ($iOriW > $iMinSize) {
                break;
            }
        }

        # get watermark image properties
        $aFilePropsWM = getimagesize($sWatermarkLocation);

        $iWatermarkW        = $aFilePropsWM[0];
        $iWatermarkH        = $aFilePropsWM[1];
        $iWatermarkType     = $aFilePropsWM[2];
        $iWatermarkMimeType = $aFilePropsWM['mime'];

        // get top positions for watermark
        if ($iPosition == self::POS_LEFT_TOP) {
            $iWatermarkX = 0 + $iMarginX;
            $iWatermarkY = 0 + $iMarginY;
        } elseif ($iPosition == self::POS_CENTER_TOP) {
            $iWatermarkX = ($iOriW / 2) - ($iWatermarkW / 2);
            $iWatermarkY = 0 + $iMarginY;
        } elseif ($iPosition == self::POS_RIGHT_TOP) {
            $iWatermarkX = $iOriW - $iWatermarkW - $iMarginX;
            $iWatermarkY = 0 + $iMarginY;
        }

        // middle positions
        if ($iPosition == self::POS_LEFT_MIDDLE) {
            $iWatermarkX = 0 + $iMarginX;
            $iWatermarkY = ($iOriH / 2) - ($iWatermarkH / 2);
        } elseif ($iPosition == self::POS_CENTER_MIDDLE) {
            $iWatermarkX = ($iOriW / 2) - ($iWatermarkW / 2);
            $iWatermarkY = ($iOriH / 2) - ($iWatermarkH / 2);
        } elseif ($iPosition == self::POS_RIGHT_MIDDLE) {
            $iWatermarkX = $iOriW - $iWatermarkW - $iMarginX;
            $iWatermarkY = ($iOriH / 2) - ($iWatermarkH / 2);
        }

        // bottom positions
        if ($iPosition == self::POS_LEFT_BOTTOM) {
            $iWatermarkX = 0 + $iMarginX;
            $iWatermarkY = $iOriH - $iWatermarkH - $iMarginY;
        } elseif ($iPosition == self::POS_CENTER_BOTTOM) {
            $iWatermarkX = ($iOriW / 2) - ($iWatermarkW / 2);
            $iWatermarkY = $iOriH - $iWatermarkH - $iMarginY;
        } elseif ($iPosition == self::POS_RIGHT_BOTTOM) {
            $iWatermarkX = $iOriW - $iWatermarkW - $iMarginX;
            $iWatermarkY = $iOriH - $iWatermarkH - $iMarginY;
        }

        # create watermark image
        switch ($iWatermarkType) {
            case IMAGETYPE_JPEG:
                $rWatermarkImage = imagecreatefromjpeg($sWatermarkLocation);
                break;
            case IMAGETYPE_GIF:
                $rWatermarkImage = imagecreatefromgif($sWatermarkLocation);
                break;
            case IMAGETYPE_PNG:
                $rWatermarkImage = imagecreatefrompng($sWatermarkLocation);
                break;
            default:
                $sErrorMsg = 'Geen geldig type voor watermerk `' . $iWatermarkMimeType . '`: png, jpg of gif';

                return false;
        }

        # create original image and add watermark
        switch ($iOriType) {
            case IMAGETYPE_JPEG:
                $rOriginalImage = imagecreatefromjpeg($sOriLocation);
                if ($iWatermarkType == IMAGETYPE_JPEG) {
                    // jpeg watermark, use alpha chanel possibility from copymerge
                    imagecopymerge($rOriginalImage, $rWatermarkImage, $iWatermarkX, $iWatermarkY, 0, 0, $iWatermarkW, $iWatermarkH, $iTransparency);
                } else {
                    // PNG or GIF watermark, just copy watermark on image
                    imagecopy($rOriginalImage, $rWatermarkImage, $iWatermarkX, $iWatermarkY, 0, 0, $iWatermarkW, $iWatermarkH);
                }

                return imagejpeg($rOriginalImage, $sOriLocation, 100);
            case IMAGETYPE_GIF:
                $rOriginalImage = imagecreatefromgif($sOriLocation);
                imagealphablending($rOriginalImage, false);
                imagesavealpha($rOriginalImage, true);

                return imagegif($rOriginalImage, $sOriLocation);
            case IMAGETYPE_PNG:
                $rOriginalImage = imagecreatefrompng($sOriLocation);
                imagealphablending($rOriginalImage, false);
                imagesavealpha($rOriginalImage, true);

                return imagepng($rOriginalImage, $sOriLocation, 0);
            default:
                $sErrorMsg = 'Geen geldig type voor watermerk `' . $iOriMimeType . '`: png, jpg of gif';

                return false;
        }

        // destroy and free memory
        if ($rOriginalImage) {
            imagedestroy($rOriginalImage);
        }

        // destroy and free memory
        if ($rWatermarkImage) {
            imagedestroy($rWatermarkImage);
        }

        return true;
    }

    /**
     * expand canvas behind image
     *
     * @param string $sOriLocation
     * @param string $sDestination
     * @param int    $iCW       width of canvas that will be created
     * @param int    $iCH       height of canvas that will be created
     * @param array  $aColor    (jpg only) array with RGB color array('r'=>255, 'g' => 255, 'b' => 255) leave empty for auto discover color
     * @param int    $iPosition position ad defined in this class
     * @param string $sErrorMsg
     * @param int    $iJpegQuality
     *
     * @return boolean
     */
    public static function expandCanvasToExactSize(
        $sOriLocation,
        $sDestination,
        $iCW,
        $iCH,
        $aColor = [
            'r' => 255,
            'g' => 255,
            'b' => 255,
        ],
        $iPosition = self::POS_CENTER_MIDDLE,
        &$sErrorMsg = null,
        $iJpegQuality = 100
    ) {

        $aFileProps = getimagesize($sOriLocation);

        $iOriW        = $aFileProps[0];
        $iOriH        = $aFileProps[1];
        $iOriType     = $aFileProps[2];
        $iOriMimeType = $aFileProps['mime'];

        // get top positions for watermark
        if ($iPosition == self::POS_LEFT_TOP) {
            $iOriX = 0;
            $iOriY = 0;
        } elseif ($iPosition == self::POS_CENTER_TOP) {
            $iOriX = ($iCW / 2) - ($iOriW / 2);
            $iOriY = 0;
        } elseif ($iPosition == self::POS_RIGHT_TOP) {
            $iOriX = $iCW - $iOriW;
            $iOriY = 0;
        }

        // middle positions
        if ($iPosition == self::POS_LEFT_MIDDLE) {
            $iOriX = 0;
            $iOriY = ($iCH / 2) - ($iOriH / 2);
        } elseif ($iPosition == self::POS_CENTER_MIDDLE) {
            $iOriX = ($iCW / 2) - ($iOriW / 2);
            $iOriY = ($iCH / 2) - ($iOriH / 2);
        } elseif ($iPosition == self::POS_RIGHT_MIDDLE) {
            $iOriX = $iCW - $iOriW;
            $iOriY = ($iCH / 2) - ($iOriH / 2);
        }

        // bottom positions
        if ($iPosition == self::POS_LEFT_BOTTOM) {
            $iOriX = 0;
            $iOriY = $iCH - $iOriH;
        } elseif ($iPosition == self::POS_CENTER_BOTTOM) {
            $iOriX = ($iCW / 2) - ($iOriW / 2);
            $iOriY = $iCH - $iOriH;
        } elseif ($iPosition == self::POS_RIGHT_BOTTOM) {
            $iOriX = $iCW - $iOriW;
            $iOriY = $iCH - $iOriH;
        }

        # copy image, resize and save
        switch ($iOriType) {
            case IMAGETYPE_JPEG:
                $rOriginalImage  = imagecreatefromjpeg($sOriLocation);
                $rTemporaryImage = imagecreatetruecolor($iCW, $iCH);

                // no color set, pick one automatically
                if (empty($aColor)) {
                    $aColorPicked = imagecolorsforindex($rOriginalImage, imagecolorat($rOriginalImage, 0, 0));
                    $aColor['r']  = $aColorPicked['red'];
                    $aColor['g']  = $aColorPicked['green'];
                    $aColor['b']  = $aColorPicked['blue'];
                }

                imagefill($rTemporaryImage, 0, 0, imagecolorallocate($rTemporaryImage, $aColor['r'], $aColor['g'], $aColor['b']));

                imagecopyresampled($rTemporaryImage, $rOriginalImage, $iOriX, $iOriY, 0, 0, $iOriW, $iOriH, $iOriW, $iOriH);

                return imagejpeg($rTemporaryImage, $sDestination, $iJpegQuality);
            case IMAGETYPE_GIF:
                $rOriginalImage  = imagecreatefromgif($sOriLocation);
                $rTemporaryImage = imagecreatetruecolor($iCW, $iCH);

                # transparency magic
                $iTransparency = imagecolortransparent($rOriginalImage);
                $iPalletSize   = imagecolorstotal($rOriginalImage);
                if ($iTransparency >= 0 && $iTransparency < $iPalletSize) {
                    $aTransparencyColor = imagecolorsforindex($rOriginalImage, $iTransparency);
                    $iTransparency      = imagecolorallocate($rTemporaryImage, $aTransparencyColor['red'], $aTransparencyColor['green'], $aTransparencyColor['blue']);
                    imagefill($rTemporaryImage, 0, 0, $iTransparency);
                    imagecolortransparent($rTemporaryImage, $iTransparency);
                } else {
                    // no color set, pick one automatically
                    if (empty($aColor)) {
                        $aColorPicked = imagecolorsforindex($rOriginalImage, imagecolorat($rOriginalImage, 0, 0));
                        $aColor['r']  = $aColorPicked['red'];
                        $aColor['g']  = $aColorPicked['green'];
                        $aColor['b']  = $aColorPicked['blue'];
                    }
                    imagefill($rTemporaryImage, 0, 0, imagecolorallocate($rTemporaryImage, $aColor['r'], $aColor['g'], $aColor['b']));
                }

                imagecopyresampled($rTemporaryImage, $rOriginalImage, $iOriX, $iOriY, 0, 0, $iOriW, $iOriH, $iOriW, $iOriH);

                return imagegif($rTemporaryImage, $sDestination);
            case IMAGETYPE_PNG:
                $rOriginalImage  = imagecreatefrompng($sOriLocation);
                $rTemporaryImage = imagecreatetruecolor($iCW, $iCH);

                /* transparantie magie */
                $iTransparency = imagecolortransparent($rOriginalImage);
                if ($iTransparency >= 0) {
                    # If we have a specific transparent color
                    $aTransparencyColor = imagecolorsforindex($rOriginalImage, $iTransparency);

                    # Get the original image's transparent color's RGB values
                    $iTransparency = imagecolorallocate($rTemporaryImage, $aTransparencyColor['red'], $aTransparencyColor['green'], $aTransparencyColor['blue']);

                    # Completely fill the background of the new image with allocated color.
                    imagefill($rTemporaryImage, 0, 0, $iTransparency);

                    # Set the background color for new image to transparent
                    imagecolortransparent($rTemporaryImage, $iTransparency);
                } else {
                    # Always make a transparent background color for PNGs that don't have one allocated already
                    # Turn off transparency blending (temporarily)
                    imagealphablending($rTemporaryImage, false);

                    # Create a new transparent color for image
                    $color = imagecolorallocatealpha($rTemporaryImage, 0, 0, 0, 127);

                    # Completely fill the background of the new image with allocated color.
                    imagefill($rTemporaryImage, 0, 0, $color);

                    # Restore transparency blending
                    imagesavealpha($rTemporaryImage, true);
                }

                imagecopyresampled($rTemporaryImage, $rOriginalImage, $iOriX, $iOriY, 0, 0, $iOriW, $iOriH, $iOriW, $iOriH);

                return imagepng($rTemporaryImage, $sDestination, 0);
            default:
                $sErrorMsg = 'Geen geldig type `' . $iOriMimeType . '`: png, jpg of gif';

                return false;
        }
        if (!empty($rTemporaryImage)) {
            imagedestroy($rTemporaryImage);
        }
    }

    /**
     *
     * @param string $sOriLocation
     * @param string $sDestination
     * @param float  $fRatio
     * @param array  $aColor (jpg only) array with RGB color array('r'=>255, 'g' => 255, 'b' => 255) leave empty for auto discover color
     * @param int    $iPosition
     * @param string $sErrorMsg
     * @param int    $iJpegQuality
     *
     * @return boolean
     */
    public static function expandCanvasToRatio(
        $sOriLocation,
        $sDestination,
        $fRatio = null,
        $aColor = [
            'r' => 255,
            'g' => 255,
            'b' => 255,
        ],
        $iPosition = self::POS_CENTER_MIDDLE,
        &$sErrorMsg = null,
        $iJpegQuality = 100
    ) {
        $aFileProps = getimagesize($sOriLocation);

        $iOriW        = $aFileProps[0];
        $iOriH        = $aFileProps[1];
        $iOriType     = $aFileProps[2];
        $iOriMimeType = $aFileProps['mime'];
        $fOriRatio    = $iOriW / $iOriH;

        if ($fOriRatio > $fRatio) {
            // fixed width
            $iCW = $iOriW;
            $iCH = $iCW / $fRatio;
        } else {
            // fixed height
            $iCH = $iOriH;
            $iCW = $fRatio * $iCH;
        }

        return self::expandCanvasToExactSize($sOriLocation, $sDestination, $iCW, $iCH, $aColor, $iPosition, $sErrorMsg, $iJpegQuality);
    }

    /**
     *
     * @param string $sOriLocation
     * @param string $sDestination
     * @param int    $iMarginT
     * @param int    $iMarginR
     * @param int    $iMarginB
     * @param int    $iMarginL
     * @param array  $aColor (jpg only) array with RGB color array('r'=>255, 'g' => 255, 'b' => 255) leave empty for auto discover color
     * @param string $sErrorMsg
     * @param int    $iJpegQuality
     *
     * @return boolean
     */
    public static function expandCanvasToMargin(
        $sOriLocation,
        $sDestination,
        $iMarginT = 0,
        $iMarginR = 0,
        $iMarginB = 0,
        $iMarginL = 0,
        $aColor = [
            'r' => 255,
            'g' => 255,
            'b' => 255,
        ],
        &$sErrorMsg = null,
        $iJpegQuality = 100
    ) {
        $aFileProps = getimagesize($sOriLocation);

        $iOriW        = $aFileProps[0];
        $iOriH        = $aFileProps[1];
        $iOriType     = $aFileProps[2];
        $iOriMimeType = $aFileProps['mime'];

        // calculate dimensions of new canvas
        $iCW = $iOriW + $iMarginR + $iMarginL;
        $iCH = $iOriH + $iMarginT + $iMarginB;

        // set X and Y from left and top margin
        $iOriX = $iMarginL;
        $iOriY = $iMarginT;

        # copy image, resize and save
        switch ($iOriType) {
            case IMAGETYPE_JPEG:
                $rOriginalImage  = imagecreatefromjpeg($sOriLocation);
                $rTemporaryImage = imagecreatetruecolor($iCW, $iCH);

                // no color set, pick one automatically
                if (empty($aColor)) {
                    $aColorPicked = imagecolorsforindex($rOriginalImage, imagecolorat($rOriginalImage, 0, 0));
                    $aColor['r']  = $aColorPicked['red'];
                    $aColor['g']  = $aColorPicked['green'];
                    $aColor['b']  = $aColorPicked['blue'];
                }

                imagefill($rTemporaryImage, 0, 0, imagecolorallocate($rTemporaryImage, $aColor['r'], $aColor['g'], $aColor['b']));

                imagecopyresampled($rTemporaryImage, $rOriginalImage, $iOriX, $iOriY, 0, 0, $iOriW, $iOriH, $iOriW, $iOriH);

                return imagejpeg($rTemporaryImage, $sDestination, $iJpegQuality);
            case IMAGETYPE_GIF:
                $rOriginalImage  = imagecreatefromgif($sOriLocation);
                $rTemporaryImage = imagecreatetruecolor($iCW, $iCH);

                # transparency magic
                $iTransparency = imagecolortransparent($rOriginalImage);
                $iPalletSize   = imagecolorstotal($rOriginalImage);
                if ($iTransparency >= 0 && $iTransparency < $iPalletSize) {
                    $aTransparencyColor = imagecolorsforindex($rOriginalImage, $iTransparency);
                    $iTransparency      = imagecolorallocate($rTemporaryImage, $aTransparencyColor['red'], $aTransparencyColor['green'], $aTransparencyColor['blue']);
                    imagefill($rTemporaryImage, 0, 0, $iTransparency);
                    imagecolortransparent($rTemporaryImage, $iTransparency);
                } else {
                    // no color set, pick one automatically
                    if (empty($aColor)) {
                        $aColorPicked = imagecolorsforindex($rOriginalImage, imagecolorat($rOriginalImage, 0, 0));
                        $aColor['r']  = $aColorPicked['red'];
                        $aColor['g']  = $aColorPicked['green'];
                        $aColor['b']  = $aColorPicked['blue'];
                    }
                    imagefill($rTemporaryImage, 0, 0, imagecolorallocate($rTemporaryImage, $aColor['r'], $aColor['g'], $aColor['b']));
                }

                imagecopyresampled($rTemporaryImage, $rOriginalImage, $iOriX, $iOriY, 0, 0, $iOriW, $iOriH, $iOriW, $iOriH);

                return imagegif($rTemporaryImage, $sDestination);
            case IMAGETYPE_PNG:
                $rOriginalImage  = imagecreatefrompng($sOriLocation);
                $rTemporaryImage = imagecreatetruecolor($iCW, $iCH);

                /* transparantie magie */
                $iTransparency = imagecolortransparent($rOriginalImage);
                if ($iTransparency >= 0) {
                    # If we have a specific transparent color
                    $aTransparencyColor = imagecolorsforindex($rOriginalImage, $iTransparency);

                    # Get the original image's transparent color's RGB values
                    $iTransparency = imagecolorallocate($rTemporaryImage, $aTransparencyColor['red'], $aTransparencyColor['green'], $aTransparencyColor['blue']);

                    # Completely fill the background of the new image with allocated color.
                    imagefill($rTemporaryImage, 0, 0, $iTransparency);

                    # Set the background color for new image to transparent
                    imagecolortransparent($rTemporaryImage, $iTransparency);
                } else {
                    # Always make a transparent background color for PNGs that don't have one allocated already
                    # Turn off transparency blending (temporarily)
                    imagealphablending($rTemporaryImage, false);

                    # Create a new transparent color for image
                    $color = imagecolorallocatealpha($rTemporaryImage, 0, 0, 0, 127);

                    # Completely fill the background of the new image with allocated color.
                    imagefill($rTemporaryImage, 0, 0, $color);

                    # Restore transparency blending
                    imagesavealpha($rTemporaryImage, true);
                }

                imagecopyresampled($rTemporaryImage, $rOriginalImage, $iOriX, $iOriY, 0, 0, $iOriW, $iOriH, $iOriW, $iOriH);
                imagepng($rTemporaryImage, $sDestination, 0);
                break;
            default:
                $sErrorMsg = 'Geen geldig type `' . $iOriMimeType . '`: png, jpg of gif';

                return false;
        }
        if (!empty($rTemporaryImage)) {
            imagedestroy($rTemporaryImage);
        }

        return true;
    }

    /**
     * handle image upload, create all needed sizes
     *
     * @param Upload $oUpload
     * @param array  $aImageSettings
     * @param string $sImagesPath
     * @param string $sTitle
     * @param array  $aErrorMsgs
     *
     * @return boolean|\Image
     */
    public static function handleImageUpload(Upload $oUpload, array $aImageSettings, $sTitle = '', &$aErrorMsgs = '')
    {

        // check if images path settings exists
        if (!empty($aImageSettings['imagesPath'])) {
            $sImagesPath = $aImageSettings['imagesPath'];
        } else {
            $aErrorMsgs[] = sysTranslations::get('imagemanager_images_path_setting_missing');

            return false;
        }

        // check if sizes exists
        if (!empty($aImageSettings['sizes'])) {
            $aImageSizes = $aImageSettings['sizes'];
        } else {
            $aErrorMsgs[] = sysTranslations::get('imagemanager_sizes_setting_missing');

            return false;
        }

        // correct orientation
        $sErrorMsg = null;
        if (extension_loaded('exif') && function_exists('exif_read_data')) {
            $aExifData = exif_read_data(DOCUMENT_ROOT . $oUpload->sNewFilePath);
            if (!empty($aExifData) && !empty($aExifData['Orientation'])) {
                switch ($aExifData['Orientation']) {
                    case 2:
                        self::flipImage(DOCUMENT_ROOT . $oUpload->sNewFilePath, DOCUMENT_ROOT . $oUpload->sNewFilePath, IMG_FLIP_HORIZONTAL, $sErrorMsg);
                        break;
                    case 3:
                        self::rotateImage(DOCUMENT_ROOT . $oUpload->sNewFilePath, DOCUMENT_ROOT . $oUpload->sNewFilePath, 180, $sErrorMsg);
                        break;
                    case 4:
                        self::rotateImage(DOCUMENT_ROOT . $oUpload->sNewFilePath, DOCUMENT_ROOT . $oUpload->sNewFilePath, 180, $sErrorMsg);
                        self::flipImage(DOCUMENT_ROOT . $oUpload->sNewFilePath, DOCUMENT_ROOT . $oUpload->sNewFilePath, IMG_FLIP_HORIZONTAL, $sErrorMsg);
                        break;
                    case 5:
                        self::rotateImage(DOCUMENT_ROOT . $oUpload->sNewFilePath, DOCUMENT_ROOT . $oUpload->sNewFilePath, 270, $sErrorMsg);
                        self::flipImage(DOCUMENT_ROOT . $oUpload->sNewFilePath, DOCUMENT_ROOT . $oUpload->sNewFilePath, IMG_FLIP_HORIZONTAL, $sErrorMsg);
                        break;
                    case 6:
                        self::rotateImage(DOCUMENT_ROOT . $oUpload->sNewFilePath, DOCUMENT_ROOT . $oUpload->sNewFilePath, 270, $sErrorMsg);
                        break;
                    case 7:
                        self::rotateImage(DOCUMENT_ROOT . $oUpload->sNewFilePath, DOCUMENT_ROOT . $oUpload->sNewFilePath, 90, $sErrorMsg);
                        self::flipImage(DOCUMENT_ROOT . $oUpload->sNewFilePath, DOCUMENT_ROOT . $oUpload->sNewFilePath, IMG_FLIP_HORIZONTAL, $sErrorMsg);
                        break;
                    case 8:
                        self::rotateImage(DOCUMENT_ROOT . $oUpload->sNewFilePath, DOCUMENT_ROOT . $oUpload->sNewFilePath, 90, $sErrorMsg);
                        break;
                    case 1:
                    default:
                        // unknown or normal orienation, do noting
                        break;
                }
            }
        }

        if (!empty($sErrorMsg)) {
            $aErrorMsgs[] = $sErrorMsg;
        }

        // make Image object and save
        $oImage = new Image();

        $oImageFile           = new ImageFile();
        $oImageFile->title    = $sTitle;
        $oImageFile->mimeType = $oUpload->sMimeType;
        $oImageFile->name     = $oUpload->sNewFileBaseName;

        // create all image resizes and crops
        $aImageFiles = [];
        foreach ($aImageSizes as $sReference => $aImageSize) {
            $oImageFileReference = clone $oImageFile;
            $sDestinationPath    = $sImagesPath . '/' . $sReference . '/' . $oImageFile->name;

            // handle resizing image
            if (!empty($aImageSize['adjustment']) && $aImageSize['adjustment'] == 'resize') {
                ## Resize
                if (!self::resizeImage(
                    DOCUMENT_ROOT . $oUpload->sNewFilePath,
                    DOCUMENT_ROOT . $sDestinationPath,
                    $aImageSize['width'],
                    $aImageSize['height'],
                    $sErrorMsg,
                    false,
                    (isset($aImageSize['quality']) ? $aImageSize['quality'] : -1)
                )) {
                    $aErrorMsgs[] = sysTranslations::get('global_image_not_resized') . $sErrorMsg; //error resizing image
                }
            }

            // handle auto crop and resize image
            if (!empty($aImageSize['adjustment']) && $aImageSize['adjustment'] == 'autoCropAndResize') {
                if (!self::autoCropAndResizeImage(
                    DOCUMENT_ROOT . $oUpload->sNewFilePath,
                    DOCUMENT_ROOT . $sDestinationPath,
                    $aImageSize['width'],
                    $aImageSize['height'],
                    $sErrorMsg,
                    $aImageSize['absoluteSize'],
                    (isset($aImageSize['quality']) ? $aImageSize['quality'] : -1)
                )) {
                    $aErrorMsgs[] = sysTranslations::get('global_image_not_resized') . $sErrorMsg; //error resizing image
                }
            }

            // if file exists, do set to image files list
            if (file_exists(DOCUMENT_ROOT . $sDestinationPath)) {
                $oImageFileReference->link      = $sDestinationPath;
                $oImageFileReference->size      = filesize(DOCUMENT_ROOT . $sDestinationPath);
                $oImageFileReference->reference = $sReference;

                $aImageFiles[] = clone $oImageFileReference;
            }
        }

        $oImage->setImageFiles($aImageFiles); //set imageFiles to Image object
        // save image
        self::saveImage($oImage);

        return $oImage;
    }

    /**
     * handle crop for cropsettings before crop
     *
     * @param Image  $oImage
     * @param array  $aImageSettings
     * @param string $sReferrer
     * @param string $sReferrerText
     *
     * @return boolean|\CropSettings
     */
    public static function handleImageCropSettings(Image $oImage, array $aImageSettings, $sReferrer, $sReferrerText)
    {
        $aCropSettings = [];

        // check if images path settings exists
        if (!empty($aImageSettings['imagesPath'])) {
            $sImagesPath = $aImageSettings['imagesPath'];
        } else {
            return false;
        }

        // get crop settings
        if (!empty($aImageSettings['cropSettings'])) {
            $aImageCropSettings = $aImageSettings['cropSettings'];
        } else {
            $aImageCropSettings = [];
        }

        // get image sizes
        if (!empty($aImageSettings['sizes'])) {
            $aImageSizes = $aImageSettings['sizes'];
        } else {
            $aImageSizes = [];
        }

        foreach ($aImageCropSettings as $aCropSettingsData) {

            // set crop settings
            $oCropSettings           = new CropSettings();
            $oCropSettings->iImageId = $oImage->imageId;

            // get reference to crop from
            if (!empty($aCropSettingsData['cropFromReference'])) {
                $oCropSettings->sReferenceName = $aCropSettingsData['cropFromReference'];
            } else {
                return false;
            }

            // get name for crop
            if (!empty($aCropSettingsData['name'])) {
                $oCropSettings->sName = $aCropSettingsData['name'];
            }

            // check aspect ratio and set is filled
            if (!empty($aCropSettingsData['aspectRatio'])) {
                $oCropSettings->setAspectRatio($aCropSettingsData['aspectRatio']['width'], $aCropSettingsData['aspectRatio']['height']);
            }

            // check if preview setting is set
            if (isset($aCropSettingsData['showPreview'])) {
                $oCropSettings->bShowPreview = $aCropSettingsData['showPreview'];
            }

            // check if preview width setting is set
            if (!empty($aCropSettingsData['maxPreviewWidth'])) {
                $oCropSettings->iMaxPreviewWidth = $aCropSettingsData['maxPreviewWidth'];
            }

            $oCropSettings->sReferrer      = $sReferrer;
            $oCropSettings->sReferrerTekst = $sReferrerText;

            // check if crops are set
            if (!empty($aCropSettingsData['crops'])) {
                foreach ($aCropSettingsData['crops'] as $sReference => $aCropData) {

                    // check width of crop
                    if (empty($aImageSizes[$sReference]['width'])) {
                        continue;
                    }
                    $sWidth = $aImageSizes[$sReference]['width'];

                    // check height of crop
                    if (empty($aImageSizes[$sReference]['height'])) {
                        continue;
                    }
                    $sHeight = $aImageSizes[$sReference]['height'];

                    // show under crop box
                    if (isset($aCropData['showUnderCropBox'])) {
                        $bShowUnderCropBox = $aCropData['showUnderCropBox'];
                    } else {
                        $bShowUnderCropBox = true;
                    }

                    // make absolute size
                    if (isset($aImageSizes[$sReference]['absoluteSize'])) {
                        $bAbsoluteSize = $aImageSizes[$sReference]['absoluteSize'];
                    } else {
                        $bAbsoluteSize = false;
                    }

                    // make absolute size
                    if (isset($aImageSizes[$sReference]['quality'])) {
                        $iJpgQuality = $aImageSizes[$sReference]['quality'];
                    } else {
                        $iJpgQuality = -1;
                    }

                    $oCropSettings->addCrop($sWidth, $sHeight, $sImagesPath . '/' . $sReference . '/' . $oImage->getImageFileByReference($oCropSettings->sReferenceName)->name, $sReference, $bShowUnderCropBox, $bAbsoluteSize, $iJpgQuality);
                }
            }
            // set crop settings in array
            $aCropSettings[] = clone $oCropSettings;
        }

        return $aCropSettings;
    }

    /**
     * rotate an image
     *
     * @param     $sOriLocation
     * @param     $sDestination
     * @param int $iRotation
     * @param     $sErrorMsg
     * @param int $mBGColor
     * @param int $iJpegQuality
     *
     * @return bool
     */
    public static function rotateImage($sOriLocation, $sDestination, $iRotation = 90, &$sErrorMsg, $mBGColor = 0, $iJpegQuality = 100)
    {
        $aFileProps = getimagesize($sOriLocation);

        $iOriType     = $aFileProps[2];
        $iOriMimeType = $aFileProps['mime'];

        # copy image, resize and save
        switch ($iOriType) {
            case IMAGETYPE_JPEG:
                $rOriginalImage = imagecreatefromjpeg($sOriLocation);
                $rRotatedImage  = imagerotate($rOriginalImage, $iRotation, $mBGColor);

                return imagejpeg($rRotatedImage, $sDestination, $iJpegQuality);
            case IMAGETYPE_GIF:
                $rOriginalImage = imagecreatefromgif($sOriLocation);
                $rRotatedImage  = imagerotate($rOriginalImage, $iRotation, $mBGColor);

                return imagegif($rRotatedImage, $sDestination);
            case IMAGETYPE_PNG:
                $rOriginalImage = imagecreatefrompng($sOriLocation);
                $rRotatedImage  = imagerotate($rOriginalImage, $iRotation, $mBGColor);

                return imagepng($rRotatedImage, $sDestination, 0);
            default:
                $sErrorMsg = 'Geen geldig type `' . $iOriMimeType . '`: png, jpg of gif';

                return false;
        }

        if (!empty($rRotatedImage)) {
            imagedestroy($rRotatedImage);
        }

        if (!empty($rOriginalImage)) {
            imagedestroy($rOriginalImage);
        }
    }

    /**
     * flip image by orientation
     *
     * @param     $sOriLocation
     * @param     $sDestination
     * @param int $iFlipMode
     * @param     $sErrorMsg
     * @param int $iJpegQuality
     *
     * @return bool
     */
    public static function flipImage($sOriLocation, $sDestination, $iFlipMode = IMG_FLIP_HORIZONTAL, &$sErrorMsg, $iJpegQuality = 100)
    {
        $aFileProps = getimagesize($sOriLocation);

        $iOriType     = $aFileProps[2];
        $iOriMimeType = $aFileProps['mime'];

        switch ($iOriType) {
            case IMAGETYPE_JPEG:
                $rImage = imagecreatefromjpeg($sOriLocation);
                imageflip($rImage, $iFlipMode);

                return imagejpeg($rImage, $sDestination, $iJpegQuality);
            case IMAGETYPE_GIF:
                $rImage = imagecreatefromgif($sOriLocation);
                imageflip($rImage, $iFlipMode);

                return imagegif($rImage, $sDestination);
            case IMAGETYPE_PNG:
                $rImage = imagecreatefrompng($sOriLocation);
                imageflip($rImage, $iFlipMode);

                return imagepng($rImage, $sDestination, 0);
            default:
                $sErrorMsg = 'Geen geldig type `' . $iOriMimeType . '`: png, jpg of gif';

                return false;
        }

        if (!empty($rImage)) {
            imagedestroy($rImage);
        }
    }

}

?>
