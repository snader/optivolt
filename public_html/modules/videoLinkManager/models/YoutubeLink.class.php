<?php

class YoutubeLink extends VideoLink
{
    public $type = Media::YOUTUBE;

    /**
     * return youtube unique video id
     *
     * @return string
     */
    public function getUniqueId()
    {
        // get the unqiue video ID -> v
        if (preg_match("#youtube\..*/watch\?.*v=([^\&]+)#i", $this->link, $aMatches)) {
            return $aMatches[1];
        } elseif (preg_match("#youtu.be/(.*)#i", $this->link, $aMatches)) {
            return $aMatches[1];
        } else {
            return false;
        }
    }

    /**
     * return youtube embed link
     *
     * @param boolean $bAutoplay
     *
     * @return string
     */
    public function getEmbedLink($bAutoplay = true)
    {
        return 'https://www.youtube.com/embed/' . $this->getUniqueId() . '?autoplay=' . ($bAutoplay ? '1' : '0') . '&rel=0';
    }

    /**
     * return thumb link
     * Youtube provides 4 different thumbs
     * 1 is a big thumb with number 0 (size: 480x360)
     * 3 are small thumbs from the movie with numbers 1-3 (size: 130x97)
     *
     * @param int    $iThumb
     * @param string $sRes
     *
     * @return String
     */
    public function getThumbLink($iThumb, $sRes = 'hq')
    {

        if ($sRes == 'mr') {
            $sRes    = 'maxresdefault';
            $aHeader = get_headers('https://img.youtube.com/vi/' . $this->getUniqueId() . '/' . $sRes . '.jpg');
        } else {
            $aHeader = get_headers('https://img.youtube.com/vi/' . $this->getUniqueId() . '/' . $sRes . $iThumb . '.jpg');
        }
        $sPos = strpos($aHeader[0], '404');
        if ($sPos === false) {
            if ($sRes == 'maxresdefault') {
                $sUrl = 'https://img.youtube.com/vi/' . $this->getUniqueId() . '/' . $sRes . '.jpg';
            } else {
                $sUrl = 'https://img.youtube.com/vi/' . $this->getUniqueId() . '/' . $sRes . $iThumb . '.jpg';
            }
        } else {
            $sUrl = 'https://img.youtube.com/vi/' . $this->getUniqueId() . '/' . $iThumb . '.jpg';
        }

        return $sUrl;
    }

    /**
     * Get the video info from Youtube.
     *
     * @return array youtube video info
     */
    public function getVideoInfo()
    {
        parse_str(file_get_contents("https://youtube.com/get_video_info?video_id=" . $this->getUniqueId()), $aVideoInfo);

        return $aVideoInfo;
    }

}
