<?php

class GenAdminController extends AdminController
{
    /**
     * @inheritdoc
     */
    protected static $allowed_actions = [
        'build',
        'flush',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $oPageLayout               = $this->getRenderEngine()
            ->getLayout();
        $oPageLayout->sWindowTitle = sysTranslations::get('gen');
        $oPageLayout->sModuleName  = sysTranslations::get('gen');
    }

    /**
     * Index action
     *
     */
    public function index()
    {
        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        $oPageLayout->sViewPath = getAdminView(sprintf('gen/%1$s', __FUNCTION__));

        $aActions = static::$allowed_actions;
        array_shift($aActions);

        $this->getRenderEngine()
            ->setVariables(
                [
                    'aActions' => $aActions,
                ]
            );
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
        $oPageLayout->sViewPath    = getAdminView(sprintf('gen/%1$s', __FUNCTION__));
    }

    /**
     * Build action
     *
     */
    public function flush()
    {
        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        $path = DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . SITE_TEMPLATE . DIRECTORY_SEPARATOR . 'cache';

        $directory    = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $fileIterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($fileIterator as $file) {
            if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['css', 'js'])) {
                unlink($file);
            }
        }

        $oPageLayout->sWindowTitle = sysTranslations::get('gen_flush');
        $oPageLayout->sViewPath    = getAdminView(sprintf('gen/%1$s', __FUNCTION__));
    }
}