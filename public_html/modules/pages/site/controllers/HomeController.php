<?php

class HomeController extends PageController
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        # Get ViewPath
        $oPageLayout->sViewPath = getSiteView('home', 'pages');
    }

}