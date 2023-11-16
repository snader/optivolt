<?php

class Cache
{

    /**
     * flushes the cache
     */
    public static function flushAll() {

        self::flushCache();

    }

    /**
     * flushes the cache
     */
    public static function flushCache() {

        $sPath = DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . SITE_TEMPLATE . DIRECTORY_SEPARATOR . 'cache';

        $oDirectory    = new RecursiveDirectoryIterator($sPath, RecursiveDirectoryIterator::SKIP_DOTS);
        $oFileIterator = new RecursiveIteratorIterator($oDirectory, RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($oFileIterator as $sFile) {
            if (in_array(pathinfo($sFile, PATHINFO_EXTENSION), ['css', 'js'])) {
                unlink($sFile);
            }
        }

    }


}