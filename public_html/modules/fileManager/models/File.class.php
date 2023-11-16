<?php

class File extends Media
{
    public $name;
    public $mimeType;
    public $size;
    public $type   = Media::FILE;
    public $online = 1;

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
        if (empty($this->title)) {
            $this->setPropInvalid('title');
        }
        if (empty($this->mimeType)) {
            $this->setPropInvalid('mimeType');
        }
        if (empty($this->size)) {
            $this->setPropInvalid('size');
        }
        parent::validate(); //validate parent (media values)
    }

    /**
     * force downloading file
     */
    public function download()
    {
        $sFilename = DOCUMENT_ROOT . $this->getLinkWithoutQueryString();

        if (!file_exists($sFilename)) {
            echo Router::httpError(404, true);
        } else {
            header('Content-type: ' . mime_content_type($sFilename));
            header('Content-Disposition: attachment; filename="' . basename($sFilename) . '"');
            header('Content-Length: ' . filesize($sFilename));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            readfile($sFilename);
        }
        exit;
    }

    /**
     * read a file directly in the browser
     */
    public function read()
    {
        $sFilename = DOCUMENT_ROOT . $this->getLinkWithoutQueryString();

        if (!file_exists($sFilename)) {
            echo Router::httpError(404, true);
        } else {
            header('Content-type: ' . mime_content_type($sFilename));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            readfile($sFilename);
        }
        exit;
    }

    /**
     * check if file online/offline may be changed
     *
     * @return bool
     */
    public function isOnlineChangeable()
    {
        return true;
    }

    /**
     * check if file is editable
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * check if file is deletable
     *
     * @return bool
     */
    public function isDeletable()
    {
        return true;
    }

    /**
     * return file extension
     */
    public function getExtension()
    {
        // extensions that start with a number, add 'x-'
        return preg_replace('/^([0-9]){1}/i', 'x-$1', strtolower(pathinfo($this->link, PATHINFO_EXTENSION)));
    }

    /**
     * Get Src attribute of file
     *
     * @return string
     */
    public function getAttr()
    {
        return sprintf('src="%1$s"',
            $this->link);
    }

    public function getLinkWithoutQueryString()
    {
        return preg_replace('/\?.*/', '', $this->link);
    }

}

?>
