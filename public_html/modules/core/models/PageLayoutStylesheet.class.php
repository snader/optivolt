<?php

/**
 * class for a stylesheet used in the page layout
 */
class PageLayoutStylesheet
{

    private $location;
    private $media     = 'screen';
    private $styles;
    private $order     = 999;
    private $urlsFixed = false; // boolean for checking if urls in stylesheets are fixed (relative to absolute)

    public function __construct($sData, $iOrder = 999, $sMedia = 'screen')
    {
        $this->order = $iOrder;
        $this->media = $sMedia;

        if (preg_match('#<link#', $sData)) {
            // link tag
            $aMatches = [];
            preg_match_all('#(href|media)="([^"]*)"#', $sData, $aMatches, PREG_SET_ORDER);
            $aAttributes = [];
            if (!empty($aMatches)) {
                // get attributes
                foreach ($aMatches AS $aMatch) {
                    $aAttributes[$aMatch[1]] = $aMatch[2];
                }
            }

            // set media when media found
            if (!empty($aAttributes['media'])) {
                $this->media = $aAttributes['media'];
            }

            // set location when href is found
            if (!empty($aAttributes['href'])) {
                $this->location = $aAttributes['href'];
            }
        } elseif (preg_match('#^(/[a-zA-Z]+|https?://)#', $sData)) {
            // direct link
            $this->location = $sData;
        } elseif (preg_match('#<style#', $sData)) {
            // style tag
            $aMatches = [];
            preg_match_all('#(media)="([^"]*)"#', $sData, $aMatches, PREG_SET_ORDER);
            $aAttributes = [];
            if (!empty($aMatches)) {
                // get attributes
                foreach ($aMatches AS $aMatch) {
                    $aAttributes[$aMatch[1]] = $aMatch[2];
                }
            }

            // set media when media found
            if (!empty($aAttributes['media'])) {
                $this->media = $aAttributes['media'];
            }

            // remove style tags and set styles
            $this->styles = preg_replace('#(</?style[^>]*>)#', '', $sData);
        } else {
            // should be plain styles then
            $this->styles = $sData;
        }
    }

    /**
     * set styles based on location or direct styles
     *
     * @return type
     */
    private function setStyles()
    {
        if ($this->styles === null) {
            if ($this->location) {
                if (preg_match('#^/[a-zA-Z]+#', $this->location)) {
                    // relative path
                    $sStyles = file_get_contents(DOCUMENT_ROOT . $this->location);
                } else {
                    // absolute path
                    $sStyles = file_get_contents($this->location);
                }

                if ($sStyles !== false) {
                    $this->styles = $sStyles;
                } else {
                    Debug::logError("", "Error getting styles", __FILE__, __LINE__, _d($this, 1, 1), Debug::LOG_IN_EMAIL);
                }
            } else {
                Debug::logError("", "Missing stylesheet file or incorrect location", __FILE__, __LINE__, _d($this, 1, 1), Debug::LOG_IN_EMAIL);
            }
        }

        // fix url path to be absolute instead of relative
        if (!$this->urlsFixed) {
            $sDirectory = dirname($this->location);
            $aReplaces  = [];

            // get all url() references in the style
            preg_match_all('#url\((["\']([^"\']*)["\'])\)#', $this->styles, $aMatches, PREG_SET_ORDER);

            foreach ($aMatches AS $aMatch) {
                $sUrl = $aMatch[2];

                // replace relative urls to absolute
                if (!preg_match('#^(/[a-zA-Z]+|https?://|//)#', $sUrl)) {
                    $aReplaces[$sUrl] = $sDirectory . '/' . $sUrl;
                }
            }

            // replace relative urls to absolute
            $this->styles    = str_replace(array_keys($aReplaces), array_values($aReplaces), $this->styles);
            $this->urlsFixed = true;
        }
    }

    /**
     * return styles from stylesheet
     *
     * @return string
     */
    public function getStyles()
    {
        if ($this->styles === null) {
            $this->setStyles();
        }

        return $this->styles;
    }

    /**
     * return the order prop
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * return the location prop
     *
     * @return int
     */
    public function getLocation()
    {
        return $this->location;
    }

}

?>