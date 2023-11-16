<?php

class FileSystem
{
    const PERMISSION = 0755;

    /**
     * Get the path to the given path and (recursively) create directory if it does not exist
     *
     * @param $sPath
     *
     * @return string
     */
    public static function getOrMakeDirectory($sPath)
    {
        if (!file_exists($sPath)) {
            mkdir($sPath, static::PERMISSION, true);
        }

        return $sPath;
    }

    /**
     * Get the path to the given path and touch the file if it does not exist
     *
     * @param $sPath
     *
     * @return string
     */
    public static function getOrTouchFile($sPath)
    {
        list($sPath, $sFile) = static::getPathAndFilename($sPath);
        $sPath = static::getOrMakeDirectory($sPath);

        $sCheckedPath = $sPath . DIRECTORY_SEPARATOR . $sFile;
        if (!file_exists($sCheckedPath) && touch($sCheckedPath)) {
            chmod($sCheckedPath, static::PERMISSION);
        }

        return $sCheckedPath;
    }

    /**
     * Write to a file
     *
     * @param string $sFilePath
     * @param string $sData
     *
     * @return int
     */
    public static function write($sFilePath, $sData)
    {

        list($sPath,) = static::getPathAndFilename($sFilePath);
        self::getOrMakeDirectory($sPath);

        if ($mSuccess = file_put_contents($sFilePath, $sData)) {
            chmod($sFilePath, static::PERMISSION);
        }

        return $mSuccess;
    }

    /**
     * Read from a file
     *
     * @param string $sPath
     *
     * @return string|bool
     *
     * @throws \ErrorException
     */
    public static function read($sPath)
    {
        if (file_exists($sPath)) {
            return static::fileGetContents($sPath);
        }

        return false;
    }

    /**
     * Move a file from one path to another
     *
     * @param string $sSourcePath
     * @param string $sDestinationPath
     *
     * @return bool
     */
    public static function move($sSourcePath, $sDestinationPath)
    {
        $sSourcePath      = static::getOrTouchFile($sSourcePath);
        $sDestinationPath = static::getOrTouchFile($sDestinationPath);

        if ($mSuccess = rename($sSourcePath, $sDestinationPath)) {
            chmod($sDestinationPath, static::PERMISSION);
        }

        return $mSuccess;
    }

    /**
     * Retrieve the path and filename separately
     *
     * @param string $sPath
     *
     * @return array (string $sPath, string $sFilename)
     */
    public static function getPathAndFilename($sPath)
    {
        $aPath = explode(DIRECTORY_SEPARATOR, $sPath);
        $sFile = array_pop($aPath);
        $sPath = implode(DIRECTORY_SEPARATOR, $aPath);

        return [
            $sPath,
            $sFile,
        ];
    }

    /**
     * (Recursively) remove a path from the filesystem
     *
     * @param string $sPath
     */
    public static function remove($sPath)
    {
        if (is_dir($sPath)) {
            $oDir = opendir($sPath);
            while ($sItem = readdir($oDir)) {
                if (!in_array($sItem, ['.', '..'])) {
                    static::remove($sPath . DIRECTORY_SEPARATOR . $sItem);
                }
            }
        } elseif (is_file($sPath)) {
            unlink($sPath);
        }
    }

    /**
     * Wrapper for file_get_contents that turns warnings into catchable ErrorExceptions
     *
     * @param string $sPath
     *
     * @return string
     * @throws \ErrorException
     */
    protected static function fileGetContents($sPath)
    {
        $sContent = @file_get_contents($sPath);
        // if an error has occured
        if ($sContent === false) {
            $aError = error_get_last();
            throw new ErrorException($e['message'] ?? sprintf('An error occured while reading `%1$s`', $sPath));
        }

        return $sContent;
    }
}