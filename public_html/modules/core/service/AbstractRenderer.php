<?php

abstract class AbstractRenderer implements RendererInterface
{
    /**
     * Template variables
     *
     * @var array
     */
    protected $variables = [];

    /**
     * Response headers
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Set the template variables
     *
     * @param array $variables
     *
     * @return $this
     */
    public function setVariables(array $variables)
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }

    /**
     * Retrieve the template variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Clear the template variables
     *
     * @return $this
     */
    public function clearVariables()
    {
        $this->variables = [];

        return $this;
    }

    /**
     * Render the variables as JSON
     *
     * @return string
     */
    public function renderJson()
    {
        $this->setHeader('Content-Type', 'text/json');

        $this->sendHeaders();

        return json_encode($this->variables);
    }

    /**
     * Render the variables as JSend
     *
     * @param string $status
     *
     * @return string
     * @throws \JSendException
     */
    public function renderJSend($status = JSendResponse::STATUS_SUCCESS)
    {
        $this->setHeader('Content-Type', 'text/json');

        $this->sendHeaders();

        return (new JSendResponse($status, $this->variables))->getJson();
    }

    /**
     * Render the contents as a (downloadable) file
     *
     * @param string      $sContent
     * @param string      $sFileType
     * @param string|null $sFileName
     *
     * @return string
     */
    public function renderFile($sContent, $sFileType = 'text/plain', $sFileName = null)
    {
        $aHeaders = [
            'Content-Length'            => strlen($sContent),
            'Pragma'                    => 'no-cache',
            'Expires'                   => 0,
            'Content-Transfer-Encoding' => 'binary',
            'Content-Description'       => 'File Transfer',
            'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Type'              => $sFileType,
        ];

        if (!is_null($sFileName)) {
            $aHeaders['Content-Disposition'] = sprintf('attachment; filename="%1$s"', $sFileName);
        }

        $this->setHeaders($aHeaders)
            ->sendHeaders();

        return $sContent;
    }

    /**
     * Set a header value
     *
     * @param string $sHeader
     *
     * @return $this
     */
    public function setHeader($sHeader, $sContent)
    {
        $this->headers[$sHeader] = $sContent;

        return $this;
    }

    /**
     * Set header values
     *
     * @param string[] $aHeaders
     *
     * @return $this
     */
    public function setHeaders(array $aHeaders)
    {
        $this->headers = array_merge($this->headers, $aHeaders);

        return $this;
    }

    /**
     * Clear a specific header value
     *
     * @param string $sHeader
     *
     * @return $this
     */
    public function clearHeader($sHeader)
    {
        if (array_key_exists($sHeader, $this->headers)) {
            unset($this->headers[$sHeader]);
        }

        return $this;
    }

    /**
     * Clear all header values
     *
     * @return $this
     */
    public function clearHeaders()
    {
        $this->headers = [];

        return $this;
    }

    /**
     * Send the headers
     *
     * @return $this
     */
    public function sendHeaders()
    {
        if (!headers_sent()) {
            foreach ($this->headers as $sHeader => $sContent) {
                header(sprintf('%1$s: %2$s', $sHeader, $sContent));
            }
        }

        return $this;
    }
}