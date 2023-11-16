<?php

class VimeoLink extends VideoLink
{

    const API_ENDPOINT = 'https://vimeo.com/api/oembed.json?url=';

    public $type = Media::VIMEO;

    /**
     * return vimeo unique video id
     *
     * @return string
     */
    public function getUniqueId()
    {
        // get the unqiue video ID -> v
        if (preg_match("/vimeo\..*\/([\d]*)/", $this->link, $aMatches)) {
            return $aMatches[1];
        } else {
            return false;
        }
    }

    /**
     * return vimeo embed link
     *
     * @param boolean $bAutoplay
     *
     * @return string
     */
    public function getEmbedLink($bAutoplay = true)
    {
        return 'https://player.vimeo.com/video/' . $this->getUniqueId() . '?autoplay=' . ($bAutoplay ? '1' : '0');
    }

    /**
     * return thumb link
     * By default, Vimeo (with the oEmbed API) returns a thumbnail with 295x221, if something else is needed, provide the width and height
     *
     * @param int $iWidth
     * @param int $iHeight
     *
     * @return string
     */
    public function getThumbLink($iWidth = null, $iHeight = null)
    {
        $sUrl = '';
        if (!empty($oVimeoVideoInfo = $this->getVideoInfo())) {
            $sUrl = $oVimeoVideoInfo->thumbnail_url;
            if (!empty($iWidth) && !empty($iHeight)) {
                $sUrl = str_replace($oVimeoVideoInfo->thumbnail_width, $iWidth, $sUrl);
                $sUrl = str_replace($oVimeoVideoInfo->thumbnail_height, $iHeight, $sUrl);
            }
            $aHeader = get_headers($sUrl);
            $sPos    = strpos($aHeader[0], '404');
            if ($sPos === false) {
                return $sUrl;
            }
        }

        return $sUrl;
    }

    /**
     * Get the video info from Vimeo.
     *
     * @return \stdClass|array vimeo video info
     */
    public function getVideoInfo()
    {
        $oJson = json_decode(file_get_contents(self::API_ENDPOINT . $this->link));
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        } else {
            return $oJson;
        }

    }

}

?>