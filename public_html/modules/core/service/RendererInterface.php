<?php

interface RendererInterface
{
    /**
     * Set the template name
     *
     * @param $template
     *
     * @return $this
     */
    public function setTemplate($template, $module = 'core');

    /**
     * Retrieve the template name
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Retrieve the layout
     *
     * @return PageLayout
     */
    public function getLayout();

    /**
     * Set the template variables
     *
     * @param array $variables
     *
     * @return $this
     */
    public function setVariables(array $variables);

    /**
     * Clear the template variables
     *
     * @return $this
     */
    public function clearVariables();

    /**
     * Render the template
     *
     * @param string|null $template
     * @param bool        $admin
     * @param string      $module
     *
     * @return string
     */
    public function render($template = null, $admin = false, $module = 'core');

    /**
     * Render the variables as JSON
     *
     * @return string
     */
    public function renderJson();

    /**
     * Render the variables as JSON
     *
     * @param string $status
     *
     * @return string
     */
    public function renderJSend($status = JSendResponse::STATUS_SUCCESS);

    /**
     * Render the contents as a (downloadable) file
     *
     * @param string $sContent
     * @param string $sFileType
     * @param string|null   $sFileName
     *
     * @return string
     */
    public function renderFile($sContent, $sFileType = 'text/plain', $sFileName = null);

    /**
     * Set a header value
     *
     * @param string $sHeader
     */
    public function setHeader($sHeader, $sContent);

    /**
     * Set header values
     *
     * @param string[] $aHeaders
     */
    public function setHeaders(array $aHeaders);

    /**
     * Clear a specific header value
     *
     * @param string $sHeader
     */
    public function clearHeader($sHeader);

    /**
     * Clear all header values
     *
     */
    public function clearHeaders();

    /**
     * Send the headers
     *
     */
    public function sendHeaders();
}