<?php

require_once DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'service' . DIRECTORY_SEPARATOR . 'ClassManifestBuilder.php';
require_once DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'service' . DIRECTORY_SEPARATOR . 'FileSystem.php';

class Autoloader
{
    /**
     * Manifest cache file name
     *
     */
    const MANIFEST_PATH = DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'init';
    const MANIFEST_FILE = self::MANIFEST_PATH . DIRECTORY_SEPARATOR . 'classManifest.json';

    /**
     * Manifest cache
     *
     * @var array
     */
    protected static $manifest = null;

    /**
     * Autoload function for registration with spl_autoload_register
     *
     */
    public static function load($className)
    {
        static::loadManifest();
        if (!isset(static::$manifest[$className])) {
            return false;
        }

        if (isset(static::$manifest[$className]) && static::$manifest[$className] && file_exists(DOCUMENT_ROOT . static::$manifest[$className])) {
            require_once DOCUMENT_ROOT . static::$manifest[$className];
        }
    }

    /**
     * Load the manifest
     *
     */
    protected static function loadManifest()
    {
        if (!static::$manifest) {
            if (FileSystem::getOrTouchFile(static::MANIFEST_FILE)) {
                try {
                    static::$manifest = json_decode(FileSystem::read(static::MANIFEST_FILE), true);
                    if (!empty(static::$manifest)) {
                        return static::$manifest;
                    }
                } catch (Throwable $e) {
                    // we're deliberately ignoring the exception, so we can have the manifest built
                }
            }

            return static::$manifest = ClassManifestBuilder::build();
        }

        return static::$manifest;
    }
}

spl_autoload_register('Autoloader::load');