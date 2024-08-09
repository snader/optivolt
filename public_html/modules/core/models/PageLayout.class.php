<?php

/**
 * class voor page layout purposes (Admin and Normal website)
 */
class PageLayout
{
    public    $sWindowTitle; //window title of browser
    public    $sViewPath; //path to view include
    public    $sStatusUpdate; //status update
    public    $sModuleName; //name of the module
    public    $sMetaKeywords; //meta keywords related to the content
    public    $sMetaDescription; //meta description related to the content
    public    $sCanonical; //canonical (url path) tag for canonical pages (don't show this on the original page)
    public    $sRelPrev; // for pagination to set previous url for google
    public    $sRelNext; // for pagination to set next url for google
    public    $sOGTitle; // og:title tag
    public    $sOGType; // og:type tag
    public    $sOGUrl; // og:url tag
    public    $sOGDescription; // og:desciption tag
    public    $sOGImage; // og:image tag
    public    $sOGImageWidth; // og:image:width tag
    public    $sOGImageHeight; // og:image:height tag
    public    $sStructuredData = null; // provide explicit clues about the meaning of a page to Google by including structured data on the page
    public    $bIndexable      = true; // Set indexable, default true
    public    $sViewport       = 'width=device-width, initial-scale=1.0, user-scalable=0'; // Set the viewport
    protected $sCrumblePath; //crumble path (can be set manually or by one of the 2 crumbepath functions)
    private   $aJavascripts    = []; //add some extra javascript lines
    private   $aStylesheets    = []; //stylesheet lines with stylesheets
    public    $sTemplateGroupName;
    public    $sTemplateName;

    /**
     * add some lines of javascript or include files
     *
     * @param string $sData
     * @param int    $iOrder
     * @param string $sPosition
     */

    public function addJavascript($sData, $iOrder = 999, $sPosition = 'bottom')
    {
        if (!isset($this->aJavascripts[$sPosition])) {
            $this->aJavascripts[$sPosition] = [];
        }
        $this->aJavascripts[$sPosition][] = new PageLayoutJavascript($sData, $iOrder, $sPosition);
        foreach ($this->aJavascripts[$sPosition] AS $iKey => $oJavascript) {
            $oJavascript->sSortValue = sprintf('%04d', $oJavascript->getOrder()) . '-' . $iKey;
        }

        // sort on object prop
        usort($this->aJavascripts[$sPosition], 'compareSortValue');
    }

    /**
     * add some lines of CSSc or include css files
     * can be a link tag, style tags, an url or plain styles
     *
     * @param string $sData
     * @param int    $iOrder
     * @param string $sMedia
     */
    public function addStylesheet($sData, $iOrder = 999, $sMedia = 'screen')
    {
        if (!isset($this->aStylesheets[$sMedia])) {
            $this->aStylesheets[$sMedia] = [];
        }
        $this->aStylesheets[$sMedia][] = new PageLayoutStylesheet($sData, $iOrder, $sMedia);
        foreach ($this->aStylesheets[$sMedia] AS $iKey => $oStylesheet) {
            $oStylesheet->sSortValue = sprintf('%04d', $oStylesheet->getOrder()) . '-' . $iKey;
        }

        // sort on object prop
        usort($this->aStylesheets[$sMedia], 'compareSortValue');
    }

    /**
     * return the array with stylesheet lines
     *
     * @return \PageLayoutStylesheet[]
     */
    public function getStylesheets($sMedia = 'screen')
    {
        if (isset($this->aStylesheets[$sMedia])) {
            return $this->aStylesheets[$sMedia];
        } else {
            return [];
        }
    }

    /**
     * return the array with javascript lines
     *
     * @return Array
     */
    public function getJavascripts($sPosition = 'bottom')
    {
        if (isset($this->aJavascripts[$sPosition])) {
            return $this->aJavascripts[$sPosition];
        } else {
            return [];
        }
    }

    /**
     * auto generate crumble path
     *
     * @param bool $bAddHome      wether or not to add the homepage
     * @param bool $bStartWithSep wether or not to start with a seperator (before the first crumble)
     *
     * @return string
     * 
     * 
     * 
     * 
     */
    public function generateAutoCrumblePath($bAddHome = true, $sSep = '', $bStartWithSep = false)
    {
        $sCrumblePath = '';

        $sUrlPath = getCurrentUrlPath();

        # split parts from the url
        preg_match_all("#([^/]+)#", $sUrlPath, $aUrlParts);

        # unset unwanted values
        $aUrlUsableParts = [];
        $bIsAdmin        = false;
        foreach ($aUrlParts[0] AS $sUrlPart) {
            if (strtolower($sUrlPart ?? '') == 'admin') {
                $bIsAdmin = true; // if admin do some things different
            }

            if (strtolower($sUrlPart ?? '') != 'admin' && strtolower($sUrlPart ?? '') != 'home') {
                $aUrlUsableParts[] = $sUrlPart;
            }
        }

        # set template
        $sTemplate = '<li class="breadcrumb-item"><a class="crumble%s" href="%s">%s</a></li>';

        # add crumbles to path
        $sUrlPath = '';
        if ($bIsAdmin) {
            $sUrlPath = '/admin';
        }

        # add home
        if ($bAddHome) {
            $sCrumblePath .= ($bStartWithSep ? $sSep : '') . sprintf($sTemplate, '', ($sUrlPath == '' ? '/' : $sUrlPath), 'Home');
        }

        $iCountUrlUsableParts = count($aUrlUsableParts);
        foreach ($aUrlUsableParts AS $iIndex => $sUrlPart) {
            if ($iIndex == 0 && !$bAddHome) {
                $sClass = ' first';
            } elseif (($iIndex + 1) == $iCountUrlUsableParts) {
                $sClass = ' last';
            } else {
                $sClass = '';
            }

            $sUrlPath     .= '/' . $sUrlPart;
            $sCrumblePath .= ($iIndex == 0 && !$bAddHome && !$bStartWithSep ? '' : $sSep) . sprintf($sTemplate, $sClass, $sUrlPath, $sUrlPart);
        }
        $this->sCrumblePath = '<ol class="breadcrumb p-0">' . $sCrumblePath . '</ol>';

        return $this->sCrumblePath;
    }

    /**
     * generate a custom crumble path based on given crumbles
     *
     * @param array $aCrumbles
     * @param bool  $bAddHome      wether or not to add the homepage
     * @param bool  $bStartWithSep wether or not to start with a seperator (before the first crumble, in case you set `home` crumble manually)
     *
     * @return string
     * 
     * 
     */
    public function generateCustomCrumblePath(array $aCrumbles = [], $bAddHome = true, $sSep = '', $bStartWithSep = false)
    {
        $sCrumblePath = '<ol class="breadcrumb float-sm-right">';
        $sTemplate    = '<li class="breadcrumb-item %s"><a itemprop="item" href="%s"><span itemprop="name">%s</span></a><meta itemprop="position" content="%s"/></li>';

        if ($bAddHome) {
            //$sCrumblePath .= ($bStartWithSep ? $sSep : '') . sprintf($sTemplate, ' first', getBaseUrl(), 'Home', '1');
            
            $sCrumblePath .= '<li class="breadcrumb-item"><a itemprop="item" href="/"><span class="mdi mdi-home" itemprop="name"></span></a></li>';
        }

        $iC             = 2;
        $iCountCrumbles = count($aCrumbles);
        foreach ($aCrumbles AS $sName => $sLink) {
            if ($iC == 2 && !$bAddHome) {
                $sClass = 'first';
            } elseif ($iC == $iCountCrumbles + 1) {
                $sClass = 'is-active';
            } else {
                $sClass = '';
            }

            $sCrumb = ($iC == 2 && !$bAddHome && !$bStartWithSep ? '' : $sSep) . sprintf($sTemplate, $sClass, $sLink, $sName, $iC);
            // 't Ain't pretty, but it get's the job done
            $sCrumblePath .= $sLink ? $sCrumb : strip_tags($sCrumb, '<span>');

            $iC++;
        }
        $this->sCrumblePath = $sCrumblePath . '</ol>';

        return $this->sCrumblePath;
    }

    /**
     * @return string
     */
    public function getCrumblePath()
    {
        return $this->sCrumblePath;
    }

    /**
     * get combined CSS styles
     *
     * @param string $sCacheKey
     * @param string $sMedia
     *
     * @return string
     */
    public function getCombinedStyles($sCacheKey, $sMedia = 'screen')
    {
        $sFilePath = SITE_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/cache/' . prettyUrlPart($sCacheKey ? $sCacheKey : 'home') . '-' . SITE_TEMPLATE . '-' . $sMedia . '.css';

        // if file exists, just return link location
        if (file_exists(DOCUMENT_ROOT . $sFilePath) && Date::strToDate(filemtime(DOCUMENT_ROOT . $sFilePath))
                ->addSeconds(CSS_CACHE_TIME)
                ->greaterThan(new Date())) {
            // do nothing, file is already creatd and up to date
        } else {
            // combine styles to new file
            $sStyles = '';
            foreach ($this->getStylesheets($sMedia) AS $oStylesheet) {
                $sStyles .= $oStylesheet->getStyles() . PHP_EOL . PHP_EOL;
            }

            file_put_contents(DOCUMENT_ROOT . '/' . $sFilePath, $sStyles);
        }

        return $sFilePath;
    }

    /**
     * get combined javascript
     *
     * @param string $sCacheKey
     * @param string $sPosition
     *
     * @return string
     */
    public function getCombinedJavascript($sCacheKey, $sPosition = 'bottom')
    {
        $sFilePath = SITE_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/cache/' . prettyUrlPart($sCacheKey ? $sCacheKey : 'home') . '-' . SITE_TEMPLATE . '-' . $sPosition . '.js';

        // if file exists, just return link location
        if (file_exists(DOCUMENT_ROOT . $sFilePath) && Date::strToDate(filemtime(DOCUMENT_ROOT . $sFilePath))
                ->addSeconds(JS_CACHE_TIME)
                ->greaterThan(new Date())) {
            // do nothing, file is already create and up to date
        } else {
            // combine styles to new file
            $sScripts = '';
            foreach ($this->getJavascripts($sPosition) AS $oJavascript) {
                $sScripts .= $oJavascript->getScript() . PHP_EOL . PHP_EOL;
            }

            file_put_contents(DOCUMENT_ROOT . $sFilePath, $sScripts);
        }

        return $sFilePath;
    }

    /**
     * @param $oObject
     *
     * @return mixed
     */
    public function getCustomCanonical($oObject)
    {
        // check if the property customCanonical exists on the object and if it's set return the custom canonical
        if (property_exists(get_class($oObject), 'customCanonical') && !empty($oObject->customCanonical)) {
            return $oObject->customCanonical;
        }
        // fallback
        if (method_exists(get_class($oObject), 'getBaseUrlPath') && !empty($oObject->getBaseUrlPath())) {
            return $oObject->getBaseUrlPath();
        }
        // return of standard canonical
        return $this->sCanonical;
    }

}