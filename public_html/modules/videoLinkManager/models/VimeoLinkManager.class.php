<?php

class VimeoLinkManager
{

    /**
     * save VimeoLink object
     *
     * @param VimeoLink $oVimeoLink
     */
    public static function saveVimeoLink(VimeoLink $oVimeoLink)
    {
        $oDb = DBConnections::get();

        # new vimeo link
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
                        VALUES (
                            ' . db_int($oVimeoLink->mediaId) . ',
                            ' . db_str(addHttps($oVimeoLink->link)) . ',
                            ' . db_str($oVimeoLink->title) . ',
                            ' . db_str($oVimeoLink->type) . ',
                            ' . db_int($oVimeoLink->online) . ',
                            ' . db_int($oVimeoLink->order) . ',
                            NOW()
                        )
                        ON DUPLICATE KEY UPDATE
                            `link`=VALUES(`link`),
                            `title`=VALUES(`title`),
                            `type`=VALUES(`type`),
                            `online`=VALUES(`online`),
                            `order`=VALUES(`order`)
                    ;';

        $oDb->query($sQuery, QRY_NORESULT);
        if (!$oVimeoLink->mediaId) {
            $oVimeoLink->mediaId = $oDb->insert_id;
        }
    }

    /**
     * Alias for saving to make it easier for VideoLinkManager
     *
     * @param \VimeoLink $oVimeoLink
     */
    public static function saveVideoLink(VimeoLink $oVimeoLink)
    {
        self::saveVimeoLink($oVimeoLink);
    }

    /**
     * get a VimeoLink by mediaId
     *
     * @param int $iMediaId
     *
     * @return VimeoLink
     */
    public static function getVimeoLinkById($iMediaId)
    {
        $sQuery = ' SELECT
                        `m`.*
                    FROM
                        `media` AS `m`
                    WHERE
                        `m`.`mediaId` = ' . db_int($iMediaId) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'VimeoLink');
    }

    /**
     * delete a VimeoLink
     *
     * @param VimeoLink $oVimeoLink
     *
     * @return boolean
     */
    public static function deleteVimeoLink(VimeoLink $oVimeoLink)
    {

        if ($oVimeoLink->isDeletable()) {
            $oDb = DBConnections::get();

            $sQuery = 'DELETE FROM `media` WHERE `mediaId` = ' . db_int($oVimeoLink->mediaId);
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update media Order
     *
     * @param array $aMediaIds
     */
    public static function updateVimeoLinkOrder(array $aMediaIds)
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

}

?>