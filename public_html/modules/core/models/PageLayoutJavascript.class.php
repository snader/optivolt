<?php

/**
 * class for a scriptheet used in the page layout
 */
class PageLayoutJavascript
{

    private $location;
    private $position = 'bottom';
    private $script;
    private $order    = 999;

    public function __construct($sData, $iOrder = 999, $sPosition = 'bottom')
    {
        $this->order    = $iOrder;
        $this->position = $sPosition;

        if (preg_match('#<script[^>]+src=#', $sData)) {
            // link tag
            $aMatches = [];
            preg_match_all('#(src)="([^"]*)"#', $sData, $aMatches, PREG_SET_ORDER);
            $aAttributes = [];
            if (!empty($aMatches)) {
                // get attributes
                foreach ($aMatches AS $aMatch) {
                    $aAttributes[$aMatch[1]] = $aMatch[2];
                }
            }

            // set location when href is found
            if (!empty($aAttributes['src'])) {
                $this->location = $aAttributes['src'];
            }
        } elseif (preg_match('#^(/[a-zA-Z]+|https?://)#', $sData)) {
            // direct link
            $this->location = $sData;
        } elseif (preg_match('#<script#', $sData)) {
            // regular script tag
            // remove script tags and set script
            $this->script = preg_replace('#(</?script[^>]*>)#', '', $sData);
        } else {
            // should be plain script then
            $this->script = $sData;
        }
    }

    /**
     * set script based on location or direct script
     *
     * @return type
     */
    private function setScript()
    {
        if ($this->script === null) {
            if ($this->location) {
                if (preg_match('#^/[a-zA-Z]+#', $this->location)) {
                    // relative path
                    $sScript = @file_get_contents(DOCUMENT_ROOT . $this->location);
                } else {
                    // absolute path
                    $sScript = @file_get_contents($this->location);
                }

                if ($sScript !== false) {
                    $this->script = $sScript;
                } else {
                    Debug::logError("", "Error getting javascript", __FILE__, __LINE__, _d($this, 1, 1), Debug::LOG_IN_EMAIL);
                }
            } else {
                Debug::logError("", "Missing javascript file or incorrect location", __FILE__, __LINE__, _d($this, 1, 1), Debug::LOG_IN_EMAIL);
            }
        } else {
            return $this->script;
        }
    }

    /**
     * return script from scriptheet
     *
     * @return string
     */
    public function getScript()
    {
        if ($this->script === null) {
            $this->setScript();
        }

        return $this->script;
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