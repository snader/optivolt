<?php

class Upload
{

    public $sName; //name of the file incl extension
    public $sMimeType; //mimeType of the file
    public $sTmpName; //temporary file name
    public $sErrorno; //error number
    public $iSize; //file size
    public $aAllowedExtentions = null; //allowed extensions
    public $sExtension; //file extension
    public $sFileName; //file name
    public $sNewPathToFile; //new relative path file location folder
    public $sNewFilename; //new file name
    public $sNewFilePath; //full file path
    public $sNewFileBaseName; //full name of the file
    public $bSuccess           = false;

    /**
     *
     * @param array   $aFile              $_FILES['filename'] array
     * @param string  $sDestinationFolder folder to put file in
     * @param string  $sNewName           new name of the file (optional)
     * @param array   $aAllowedExtensions allowed extensions (optional)
     * @param boolean $bCheckMime         do check mimetype-extension pair in database DEFAULT TRUE
     * @param string  $sPrefix            add prefix to file basename (optional)
     * @param boolean $bAllowOverride     overwrite file if existing DEFAULT FALSE
     *
     * @return mixed nothing or error
     */
    public function __construct(array $aFile, $sDestinationFolder, $sNewName = null, $aAllowedExtensions = null, $bCheckMime = true, $sPrefix = null, $bAllowOverride = false)
    {

        # check if an array is given
        if (!is_array($aFile)) {
            return false;
        }

        # file properties
        $this->sName    = $aFile['name'];
        $this->sTmpName = $aFile['tmp_name'];
        $this->sErrorno = $aFile['error'];
        $this->iSize    = $aFile['size'];

        // check real mime type
        $oFinfo          = finfo_open(FILEINFO_MIME_TYPE);
        $this->sMimeType = finfo_file($oFinfo, $this->sTmpName);

        # extract extension and file name
        $this->sExtension = strtolower(pathinfo($this->sName, PATHINFO_EXTENSION) ?? '');
        $this->sFileName  = prettyUrlPart(pathinfo($this->sName, PATHINFO_FILENAME));

        # set location folder
        $this->sNewPathToFile = preg_replace('/(.*)(\/)$/', '$1', $sDestinationFolder); //remove last slash
        # no name? maybe .htaccess? show error
        if (empty($this->sFileName)) {
            # could be a hacker or a real missing name
            Debug::logError('U4', 'hacker or a real missing name', __FILE__, __LINE__, 'FILE: ' . $this->sFileName . ', MIME: ' . $this->sMimeType . ', EXTENSION: ' . $this->sExtension, Debug::LOG_IN_EMAIL);
            $this->sErrorno = 'U4';
            $this->bSuccess = false;

            return;
        }

        # set filename
        $this->sNewFilename = ($sPrefix === null ? '' : $sPrefix . '_') . ($sNewName === null ? $this->sFileName : prettyUrlPart($sNewName));

        # set allowed extensions
        $this->aAllowedExtentions = $aAllowedExtensions;

        # if error, set errormessage and return
        if ($this->sErrorno !== 0) {
            $this->bSuccess = false;

            return;
        }

        # check extension (always block php files)
        if ($this->sExtension == 'php' || $this->sExtension == 'js' || (is_array($this->aAllowedExtentions) && !in_array($this->sExtension, $this->aAllowedExtentions))) {
            $this->sErrorno = 'U1';
            $this->bSuccess = false;

            return;
        }

        # check mime type and extension pair
        if ($bCheckMime && !UploadManager::isValidMimeExtCombi($this->sMimeType, $this->sExtension)) {

            # could be a hacker or a missing mime-type extension combination
            Debug::logError('U3', 'hacker or a missing mime-type extension combination', __FILE__, __LINE__, 'FILE: ' . $this->sName . ', MIME: ' . $this->sMimeType . ', EXTENSION: ' . $this->sExtension, Debug::LOG_IN_EMAIL);

            $this->sErrorno = 'U3';
            $this->bSuccess = false;

            return;
        }

        $sUnique = ''; //addition for making filename unique
        if ($bAllowOverride === false) {
            # make unique filename
            $iT = 1; //counter for making unique addition
            while (file_exists(DOCUMENT_ROOT . $this->sNewPathToFile . '/' . $this->sNewFilename . $sUnique . '.' . $this->sExtension)) {
                $sUnique = '(' . $iT . ')';
                $iT++;
            }
        }

        # set some values for using outside class
        $this->sNewFilename     = $this->sNewFilename . $sUnique;
        $this->sNewFilePath     = $this->sNewPathToFile . '/' . $this->sNewFilename . '.' . $this->sExtension;
        $this->sNewFileBaseName = $this->sNewFilename . '.' . $this->sExtension;

        # move uploaded file to new location
        if (FileSystem::move($this->sTmpName, DOCUMENT_ROOT . $this->sNewFilePath)) {
            $this->bSuccess = true;
        } else {
            $this->sErrorno = 'U2';
            # probably no permission
            Debug::logError('U2', 'Can\'t move uploaded file to folder', __FILE__, __LINE__, 'FILE: `' . $this->sName . '` DESTINATION: `' . DOCUMENT_ROOT . $this->sNewFilePath . '`', Debug::LOG_IN_EMAIL);
            $this->bSuccess = false;
        }
    }

    /**
     * return the error message based on the error number
     *
     * @return string
     */
    public function getErrorMessage()
    {
        switch ($this->sErrorno) {
            case UPLOAD_ERR_INI_SIZE:
                return "Bestand is te groot";
            case UPLOAD_ERR_FORM_SIZE:
                return "Bestand is te groot";
            case UPLOAD_ERR_PARTIAL:
                return "Bestand slechts gedeeltelijk geüpload";
            case UPLOAD_ERR_NO_FILE:
                return "Geen bestand geselecteerd";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Bestand kon niet geüpload worden, neem contact op met LandgoedVoorn";
            case UPLOAD_ERR_CANT_WRITE:
                return "Bestand kon niet geüpload worden, neem contact op met LandgoedVoorn";
            case UPLOAD_ERR_EXTENSION:
                return "Extensie niet geldig voor uploaden";
            case 'U1':
                return 'Bestand extensie niet geldig: `' . $this->sExtension . '`';
            case 'U2':
                return 'Er is iets misgegaan met het verplaatsen van het geüploade bestand, probeer nog eens of neem contact op met LandgoedVoorn';
            case 'U3':
                return 'Bestand combinatie extensie/type niet geldig: `' . $this->sExtension . '`/`' . $this->sMimeType . '`';
            case 'U4':
                return 'Bestand heeft geen naam:';
            case UPLOAD_ERR_OK://Loose comparison keep latest place for 0
                return "Bestand succesvol geüpload";
            default:
                break;
        }
    }

}

?>