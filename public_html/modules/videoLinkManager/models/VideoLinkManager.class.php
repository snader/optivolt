<?php

class VideoLinkManager
{
    const TYPE_YOUTUBE = Media::YOUTUBE;
    const TYPE_VIMEO   = Media::VIMEO;

    protected static $supported = [
        'youtube.com' => self::TYPE_YOUTUBE,
        'youtu.be'    => self::TYPE_YOUTUBE,
        'vimeo.com'   => self::TYPE_VIMEO,
        'player.vimeo.com'   => self::TYPE_VIMEO,
    ];

    /**
     * @param      $iVideoLink
     * @param bool $bShowAll
     *
     * @return \VimeoLink|\YoutubeLink|null
     */
    public static function getVideoLinkById($iVideoLink, $bShowAll = true)
    {
        $sWhere = '';
        if (!$bShowAll) {
            $sWhere .= ' AND `m`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `m`.*
                    FROM
                        `media` AS `m`
                    WHERE
                        `m`.`mediaId` = ' . db_int($iVideoLink) . '
                    ' . $sWhere . '
                    ;';
        $oDb    = DBConnections::get();

        if ($aResult = $oDb->query($sQuery, QRY_UNIQUE_ARRAY)) {
            switch ($aResult['type']) {
                case static::TYPE_VIMEO:
                    return new VimeoLink($aResult);
                case static::TYPE_YOUTUBE:
                    return new YoutubeLink($aResult);
            }
        }

        return null;
    }

    /**
     * @param array $aData
     * @param bool  $bOnlineStatus
     *
     * @return \YoutubeLink|\VimeoLink|bool
     */
    public static function saveVideoLink(array $aData, $bOnlineStatus = false)
    {
        // Checking if we have the 2 required fields
        if (!empty($aData['title']) && !empty($aData['link'])) {
            $sType    = static::getType($aData['link']);
            $sClass   = sprintf('%1$sLink', ucfirst($sType));
            $sManager = sprintf('%1$sManager', $sClass);

            /* @var \VimeoLink|\YoutubeLink|mixed $sClass */
            if (!class_exists($sClass) || !class_exists($sManager)) {
                return false;
            }

            /* @var \VimeoLink|\YoutubeLink|mixed $oLink */
            $oLink = new $sClass(
                [
                    'mediaId' => $aData['mediaId'] ?? null,
                    'link'    => $aData['link'],
                    'title'   => $aData['title'],
                    'type'    => $sType,
                    'order'   => 9999,
                ]
            );

            // When online status is changed
            if ($bOnlineStatus) {
                $oLink->online = $aData['online'];
            }

            if ($oLink->isValid()) {
                /* @var \VimeoLinkManager|\YoutubeLinkManager $sManager */
                $sManager::saveVideoLink($oLink);

                return $oLink;
            } else {
                Debug::logError("", "VideoLink module php validate error", __FILE__, __LINE__, "Tried to save VideoLink with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);

                return false;
            }
        }

        return false;
    }

    /**
     *
     * @param string $sUrl
     *
     * @return string|bool
     */
    public static function getType($sUrl)
    {
        $sUrl = preg_replace('/https?:\/\/(?:www\.)?/', '', $sUrl);
        foreach (static::$supported AS $sDomain => $sType) {
            if (strpos($sUrl, $sDomain) === 0) {
                return $sType;
            }
        }

        return false;
    }

    /**
     * Delete VideoLink
     *
     * @param \Media $oVideoLink
     *
     * @return bool
     */
    public static function deleteVideoLink(Media $oVideoLink)
    {
        $oDb = DBConnections::get();

        $sQuery = 'DELETE FROM `media` WHERE `mediaId` = ' . db_int($oVideoLink->mediaId);
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * update media Order
     *
     * @param array $aMediaIds
     */
    public static function updateVideoLinkOrder(array $aMediaIds)
    {
        # start query, uses on duplicate key
        $sQuery = "";

        # add all mediaIds and values
        $iT      = 1; //counter
        $sValues = '';
        foreach ($aMediaIds AS $iMediaId) {
            $sValues .= ($sValues == '' ? '' : ',') . '(' . db_int($iMediaId) . ', ' . db_int($iT) . ')';
            $iT++;
        }
        $sQuery .= 'INSERT INTO
                        `media`
                    (
                        `mediaId`,
                        `order`
                    )
                    VALUES
                    ' . $sValues . '
                    ON DUPLICATE KEY UPDATE
                        `order`=VALUES(`order`)
            ;';

        if (count($aMediaIds) > 0) {
            $oDb = DBConnections::get();
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    /**
     * Perform given query and return the appropriate video link objects
     *
     * @param $sQuery
     *
     * @return mixed
     */
    public static function getVideoLinkByQuery($sQuery)
    {
        $aResult = DBConnections::get()
            ->query($sQuery, QRY_ASSOC);
        array_walk($aResult, function (&$mRecord) {
            switch ($mRecord['type']) {
                case Media::YOUTUBE:
                    $mRecord = new YoutubeLink($mRecord);
                    break;
                case Media::VIMEO:
                    $mRecord = new VimeoLink($mRecord);
                    break;
                default:
                    $mRecord = null;
            }
        });
        array_filter($aResult);

        return $aResult;
    }
}