<?php

class Engine
{
    use Initializable;
    /**
     * Constants manifest paths
     *
     */
    const ADMIN_CONTROLLER_MANIFEST_PATH  = DOCUMENT_ROOT . '/init/adminRoutes.json';
    const SITE_CONTROLLER_MANIFEST_PATH   = DOCUMENT_ROOT . '/init/siteRoutes.json';
    const INSTALLED_MODULES_MANIFEST_PATH = DOCUMENT_ROOT . '/init/mods.json';

    /**
     * Constants default manifests
     */
    const DEFAULT_ADMIN_CONTROLLER_MANIFEST = [
        ""                      => [
            "module"     => "core",
            "controller" => "home",
        ],
        "login"                 => [
            "module"     => "core",
            "controller" => "login",
        ],
        "gebruikers"            => [
            "module"     => "core",
            "controller" => "user",
        ],
        "modules"               => [
            "module"     => "core",
            "controller" => "module",
        ],
        "crop"                  => [
            "module"     => "core",
            "controller" => "crop",
        ],
        "instellingen"          => [
            "module"     => "core",
            "controller" => "settings",
        ],
        "system-translations"   => [
            "module"     => "core",
            "controller" => "systemTranslation",
        ],
        "toegangsgroepen"       => [
            "module"     => "core",
            "controller" => "userAccessGroup",
        ],
        "install"               => [
            "module"     => "core",
            "controller" => "install",
        ],
        "access-management"     => [
            "module"     => "core",
            "controller" => "accessLog",
        ],
        "gen"                   => [
            "module"     => "core",
            "controller" => "gen",
        ],
        "filemanagement"        => [
            "module"     => "fileManager",
            "controller" => "fileManagement",
        ],
        "imagemanagement"       => [
            "module"     => "imageManager",
            "controller" => "imageManagement",
        ],
        "linkmanagement"        => [
            "module"     => "linkManager",
            "controller" => "linkManagement",
        ],
        "videolinkmanagement" => [
            "module"     => "videoLinkManager",
            "controller" => "videoLinkManagement",
        ],
        "paginas"               => [
            "module"     => "pages",
            "controller" => "page",
        ],
        "brandbox"              => [
            "module"     => "brandboxItems",
            "controller" => "brandboxItem",
        ],
    ];
    const DEFAULT_SITE_CONTROLLER_ROUTES    = [
        "gen"     => [
            "module"     => "core",
            "controller" => "gen",
        ],
        "uploads" => [
            "module"     => "core",
            "controller" => "uploads",
        ],
        "robots"  => [
            "module"     => "core",
            "controller" => "robots",
        ],
        "errors"  => [
            "module"     => "pages",
            "controller" => "errors",
        ],
        "404"     => [
            "module"     => "pages",
            "controller" => "errors",
        ],
        "403"     => [
            "module"     => "pages",
            "controller" => "errors",
        ],
    ];
    const DEFAULT_INSTALLED_MODULES         = [
        'core' => [],
    ];

    /**
     * Initialize all initializable singletons
     */
    public static function init()
    {
        if (!static::isInitialized()) {
            Server::init();
            Cookie::init();
            Request::init();

            if (moduleExists('conversions')) {
                ConversionSource::init();
            }

            static::initialize();
        }
    }

    /**
     * @return array
     */
    public static function getAdminControllerManifest()
    {
        if (!($sManifestContents = FileSystem::read(static::ADMIN_CONTROLLER_MANIFEST_PATH)) || ($aManifest = json_decode($sManifestContents, true)) === false) {
            $aManifest = static::DEFAULT_ADMIN_CONTROLLER_MANIFEST;
            FileSystem::write(static::ADMIN_CONTROLLER_MANIFEST_PATH, json_encode($aManifest, true));
        }

        return $aManifest;
    }

    /**
     * @return array
     */
    public static function getSiteControllerManifest()
    {
        if (!($sManifestContents = FileSystem::read(static::SITE_CONTROLLER_MANIFEST_PATH)) || ($aManifest = json_decode($sManifestContents, true)) === false) {
            $aManifest = static::DEFAULT_SITE_CONTROLLER_ROUTES;
            FileSystem::write(static::SITE_CONTROLLER_MANIFEST_PATH, json_encode($aManifest, true));
        }

        return $aManifest;
    }

    /**
     * @return array
     */
    public static function getInstalledModulesManifest()
    {
        if (!($sManifestContents = FileSystem::read(static::INSTALLED_MODULES_MANIFEST_PATH)) || ($aManifest = json_decode($sManifestContents, true)) === false) {
            $aManifest = static::DEFAULT_INSTALLED_MODULES;
            FileSystem::write(static::INSTALLED_MODULES_MANIFEST_PATH, json_encode($aManifest, true));
        }

        return $aManifest;
    }
}