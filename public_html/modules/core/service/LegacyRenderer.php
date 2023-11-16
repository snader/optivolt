<?php

class LegacyRenderer extends AbstractRenderer
{
    /**
     * Name of the template to render
     *
     * @var string
     */
    protected $template;

    /**
     * Name of the module for the template
     *
     * @var string
     */
    protected $module;

    /**
     * Name of the template type
     *
     * @var string
     */
    protected $type;

    /**
     * PageLayout
     *
     * @var PageLayout
     */
    protected $layout;

    /**
     * Set the template
     *
     * @param string $template
     * @param string $module
     *
     * @return $this
     * @throws \LegacyRendererException
     */
    public function setTemplate($template, $module = 'core', $type = 'includes')
    {
        if (!is_string($template)) {
            throw new LegacyRendererException(sprintf('Template should be a string. %1$s given.', gettype($template)));
        }

        if (!is_string($module)) {
            throw new LegacyRendererException(sprintf('Module should be a string. %1$s given.', gettype($module)));
        }

        if (!is_string($type)) {
            throw new LegacyRendererException(sprintf('Type should be a string. %1$s given.', gettype($type)));
        }

        $this->template = $template;
        $this->module   = $module;
        $this->type     = $type;

        return $this;
    }

    /**
     * Retrieve the template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Retrieve the layout
     *
     * @return \PageLayout
     */
    public function getLayout()
    {
        if (!$this->layout) {
            $this->layout = new PageLayout();
        }

        return $this->layout;
    }

    /**
     * Render the template
     *
     * @param string|null $template
     * @param bool        $admin
     * @param string      $module
     *
     * @return string
     * @throws \LegacyRendererException
     */
    public function render($template = null, $admin = false, $module = 'core')
    {
        try {
            if (!is_null($template)) {
                $this->setTemplate($template, $module);
            }
            if (!$this->template) {
                throw new LegacyRendererException('Rendering called, but no template was given.');
            }

            if (!in_array(SITE_TEMPLATE, scandir(SYSTEM_THEMES_FOLDER))) {
                throw new LegacyRendererException('Rendering called, but selected theme was not found.');
            }

            if ($this->variables) {
                extract($this->variables);
            }

            $oPageLayout = $this->getLayout();

            $this->sendHeaders();

            ob_start();
            include($admin ? getAdminView($this->template, $this->module) : getSiteView($this->template, $this->module, $this->type));

            return ob_get_clean();

        } catch (LegacyRendererException $e) {
            debug::logError('400', $e->getMessage(), __FILE__, __LINE__, 'THEME: ' . SITE_TEMPLATE, Debug::LOG_IN_EMAIL);

            if (DEBUG) {
                die($e->getMessage());
            }

            die(sysTranslations::get('core_render_error'));
        }
    }

}