<?php

class GenController extends CoreController
{
    /**
     * @inheritdoc
     */
    protected static $allowed_actions = [
        'build',
    ];

    public function init(){
        parent::init();

        if (Request::isCli()) {
            $this->end();
        }
    }

    /**
     * @inheritdoc
     */
    public function index()
    {
        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        $oPageLayout->sViewPath = getSiteView(sprintf('gen/%1$s', __FUNCTION__));
    }

    /**
     * Build action
     *
     */
    public function build()
    {
        $this->getRenderEngine()
            ->setVariables(
                [
                    'aClassManifest' => ClassManifestBuilder::build(),
                ]
            );

        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        $oPageLayout->sWindowTitle = sysTranslations::get('gen_build');
        $oPageLayout->sViewPath    = getSiteView(sprintf('gen/%1$s', __FUNCTION__));

    }

    /**
     * @inheritdoc
     */
    public function render($template = 'bare', $module = 'core')
    {
        return parent::render($template, $module);
    }

}