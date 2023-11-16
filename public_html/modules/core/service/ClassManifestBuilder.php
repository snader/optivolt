<?php

ini_set('memory_limit', '2G');

require_once DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'service' . DIRECTORY_SEPARATOR . 'FileSystem.php';

class ClassManifestBuilder
{
    /**
     * File extension as a string. Defaults to ".php".
     *
     * @var string
     */
    protected static $fileExt = 'php';

    /**
     * Root paths to iterate through
     *
     * @var array
     */
    protected static $classPaths = [
        DOCUMENT_ROOT . '/libs',
        DOCUMENT_ROOT . '/modules',
    ];

    protected static $manifestPath = Autoloader::MANIFEST_PATH;
    /**
     * Path to the manifest file
     *
     * @var string
     */
    protected static $manifestFile = Autoloader::MANIFEST_FILE;

    /**
     * Build the class manifest
     *
     */
    public static function build()
    {
        return static::classManifest();
    }

    /**
     * Gather the manifests
     *
     * @return array
     */
    protected static function classManifest()
    {
        $aManifest = [];
        foreach (static::$classPaths as $classPath) {
            $aManifest = array_merge($aManifest, static::collect($classPath));
        }

        FileSystem::getOrMakeDirectory(static::$manifestPath);
        static::write(static::$manifestFile, $aManifest);

        return $aManifest;
    }

    /**
     * Collect the manifest by given path
     *
     * @param $sPath
     *
     * @return array
     */
    protected static function collect($sPath)
    {
        if (!file_exists($sPath)) {
            return [];
        }

        $oDirectory    = new RecursiveDirectoryIterator($sPath, RecursiveDirectoryIterator::SKIP_DOTS);
        $oFileIterator = new RecursiveIteratorIterator($oDirectory, RecursiveIteratorIterator::LEAVES_ONLY);

        $aManifest = [];
        /** @var SplFileInfo $sFile */
        foreach ($oFileIterator as $sFile) {
            if (pathinfo($sFile, PATHINFO_EXTENSION) == static::$fileExt) {
                $sPhpFile        = file_get_contents($sFile);
                $aTokens         = token_get_all($sPhpFile);
                $bClassToken     = false;
                $bNamespaceToken = false;
                $sNamespace      = '';
                foreach ($aTokens as $i => $mToken) {
                    if (is_array($mToken)) {
                        switch (true) {
                            // next token is our namespace
                            case $mToken[0] === T_NAMESPACE:
                                $bNamespaceToken = true;
                                break;
                            // our namespace
                            case $bNamespaceToken && $mToken[0] === T_STRING:
                                $sNamespace .= $mToken[1] . '\\';
                                break;
                            // next token is our class name
                            // token before that must be whitespace
                            // because class declarations start at the beginning of the line
                            case in_array(
                                    $mToken[0],
                                    [
                                        T_CLASS,
                                        T_INTERFACE,
                                        T_TRAIT,
                                    ]
                                ) &&
                                    ($aTokens[$i - 1][0] !== T_DOUBLE_COLON):
//                                Dumpert::dump('we got keyword class', $sFile->getPath(), $sFile->getFilename(), $i, $mToken, $aTokens[$i-1], token_name($aTokens[$i-1][0]));
                                $bClassToken = true;
                                break;
                            // our class name
                            case $bClassToken && $mToken[0] === T_STRING:
                                $aManifest[$sNamespace . $mToken[1]] = static::getRelativePath($sFile->getPathname());
                                $bClassToken                         = false;
                                break 2;
                        }
                    } else {
                        switch (true) {
                            // namespace declaration is finished
                            case $bNamespaceToken && $mToken === ';':
                                $bNamespaceToken = false;
                                break;
                        }
                    }
                }
            }
        }

        return $aManifest;
    }

    /**
     * Retrieve the path relative to the document root
     *
     * @param string $sPathName
     *
     * @return string
     */
    protected static function getRelativePath($sPathName)
    {
        return str_replace(DOCUMENT_ROOT, '', $sPathName);
    }

    /**
     * Write the manifest data to a given location
     *
     * @param string $sFile
     * @param array  $aData
     */
    protected static function write($sFile, $aData)
    {
        try {
            $aData = json_encode($aData);
            $sFile = FileSystem::getOrTouchFile($sFile);
            FileSystem::write($sFile, $aData);
        } catch (Exception $e) {
            // @todo exception handling
        }
    }
}