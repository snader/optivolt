<?php

class ZipHandler
{
    const ZIP_EXTENSION = '.zip';

    /**
     * The zip file itself
     * @var \ZipArchive $zipFile
     */
    private static $zipFile;

    /**
     * Title of the zip file
     * @var string $zipTitle
     */
    private static $zipTitle;

    /**
     * Initialize the default check + fill $zipFile
     *
     * @param $sTitle
     *
     * @throws \Exception
     */
    private static function init($sTitle)
    {
        static::$zipTitle = static::checkZipTitle($sTitle);
        static::$zipFile  = new ZipArchive();

    }

    /**
     * Zip multiple files by array of paths (optionally with name)
     *
     * @param array $aFilePaths
     * @param null  $sTitle
     *
     * @return string
     * @throws \ZipHandlerException
     */
    public static function zipFiles(array $aFilePaths, $sTitle = null)
    {
        static::init($sTitle);
        static::$zipFile->open(static::getZipPath(static::$zipTitle), ZipArchive::CREATE);
        foreach ($aFilePaths as $sFilePath){
            if (static::checkFilePath($sFilePath)){
                static::putFileInZip($sFilePath);
            }
        }
        static::$zipFile->close();
        return static::getZipPath(static::$zipTitle);
    }

    /**
     * Zip a file by path (optionally with title)
     *
     * @param string $sFilePath
     * @param null   $sTitle
     *
     * @return false|string
     * @throws \ZipHandlerException
     */
    public static function zipFileByString(string $sFilePath, $sTitle = null)
    {
        static::init($sTitle);

        if (static::checkFilePath($sFilePath)) {
            static::$zipFile->open(static::getZipPath(static::$zipTitle), ZipArchive::CREATE);
            static::putFileInZip($sFilePath);
            static::$zipFile->close();
        }

        return static::getZipPath(static::$zipTitle);
    }

    /**
     * Insert file into static zipfile
     *
     * @param $sFilePath
     */
    public static function putFileInZip($sFilePath)
    {
        static::$zipFile->addFile($sFilePath, basename($sFilePath));
    }

    /**
     * Get /tmp zip file path
     *
     * @param $sTitle
     *
     * @return string
     */
    private static function getZipPath($sTitle)
    {
        $sTmpDir = sys_get_temp_dir();

        return $sTmpDir . (preg_match('/\/$/', $sTmpDir) ? null : '/') . $sTitle . static::ZIP_EXTENSION;
    }

    /**
     * Check if zip file name is valid
     *
     * @param $sTitle
     *
     * @return String
     * @throws \Exception
     */
    private static function checkZipTitle($sTitle)
    {
        if (empty($sTitle)) {
            $sTitle = substr(hash('md5', random_bytes(5)), 0, 12);
        }

        return prettyUrlPart($sTitle);
    }

    /**
     * Check if given file is valid and exists on server
     *
     * @param $sFilePath
     *
     * @return bool
     * @throws \ZipHandlerException
     */
    private static function checkFilePath($sFilePath)
    {
        if (!file_exists($sFilePath)) {
            throw new ZipHandlerException('File could not be found: ' . $sFilePath, '404');
        }

        return true;
    }

}