<?php

class ModuleGeneratorItemBuilder
{

    protected static $oModule = null;

    protected static $newModuleBaseLocation = null;

    protected static $newModuleThemeBaseLocation = null;

    protected static $buildComponentPath = '/moduleGenerator/build/components';

    protected static $aFilesNames = [
        'settings' => 'settings.json',
        'install'  => 'install.php',
    ];

    # set module object
    protected static function setModule(ModuleGeneratorItem $oModuleGeneratorItem): void
    {
        self::$oModule = $oModuleGeneratorItem;
    }

    /**
     * get passed module
     *
     * @return object
     */
    protected static function getModule()
    {
        return self::$oModule;
    }

    /**
     * set module base path
     *
     * @param string $sModuleBasePath
     */
    protected static function setNewModuleBase(string $sModuleBasePath): void
    {
        self::$newModuleBaseLocation = $sModuleBasePath;
    }

    /**
     * get base path
     *
     * @return string
     */
    protected static function getNewModuleBase()
    {
        return self::$newModuleBaseLocation;
    }

    /**
     * set template module base path
     *
     * @param string $sModuleThemeBasePath
     */
    protected static function setNewModuleThemeBase(string $sModuleThemeBasePath): void
    {
        self::$newModuleThemeBaseLocation = $sModuleThemeBasePath;
    }

    /**
     * get template base path
     *
     * @return string
     */
    protected static function getNewModuleThemeBase()
    {
        return self::$newModuleThemeBaseLocation;
    }

    /**
     * @param \ModuleGeneratorItem $oModuleGeneratorItem
     */
    public static function build(ModuleGeneratorItem $oModuleGeneratorItem): void
    {

        # make object available for whole class
        self::setModule($oModuleGeneratorItem);

        # define base path of module
        self::setNewModuleBase(SYSTEM_MODULES_FOLDER . '/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName));

        # define template base path of module
        self::setNewModuleThemeBase(SYSTEM_THEMES_FOLDER . '/' . SITE_TEMPLATE);

        # make dirs
        self::makeDirectories();

        # make install
        self::generateInstall();

        # make readme
        self::generateReadme();

        # generate settings JSON
        if (self::getModule()->hasImages) {
            self::generateSettingsJson();
        }

        # generate module model
        self::generateModuleModel();

        # generate module model manager
        self::generateModuleManagerModel();

        # generate admin controller
        self::generateModuleAdminController();

        # generate admin view - overview
        self::generateModuleAdminViewOverview();

        # generate admin view - detail
        self::generateModuleAdminViewDetail();

        # generate admin view - change order
        self::generateModuleAdminViewChangeOrder();

        if (self::getModule()->hasCategories) {
            # generate module category model
            self::generateModuleCategoryModel();

            # generate module model category manager
            self::generateModuleCategoryManagerModel();

            # generate admin category controller
            self::generateModuleCategoryAdminController();

            # generate admin view - overview
            self::generateModuleCategoryAdminViewOverview();

            # generate admin view - detail
            self::generateModuleCategoryAdminViewDetail();

            # generate admin view - change order
            self::generateModuleCategoryAdminViewChangeOrder();
        }

        if (self::getModule()->hasUrls) {

            # generate FE controller
            self::generateModuleController();

            # generate FE view - detail
            self::generateModuleViewDetail();

            # generate FE view - overview
            self::generateModuleViewOverview();

            if (self::getModule()->hasCategories) {
                # generate FE view - category
                self::generateModuleViewCategory();
            }

            # generate FE snippet - navigation sub
            self::generateModuleSnippetNavigationSub();
        }

        # Build class manifest to add new classes
        ClassManifestBuilder::build();

    }

    /**
     * builds settings.json file
     */
    protected static function generateSettingsJson(): void
    {

        $sPartialContent = '';
        if (self::getModule()->hasImages) {
            # read content from template
            $sPartialContent .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/settings/images.txt');
        }

        # define replace values and combine code
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), str_replace('{{code}}', $sPartialContent, FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/settings/template.txt')));

        # write new file
        FileSystem::write(self::getNewModuleThemeBase() . '/templates/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName) . '/' . self::$aFilesNames['settings'], $sContent);

    }

    /**
     * make the needed directories
     */
    protected static function makeDirectories(): void
    {

        # defining locations
        $aLocations   = [];
        $aLocations[] = self::getNewModuleBase();
        $aLocations[] = self::getNewModuleBase() . ADMIN_FOLDER;
        $aLocations[] = self::getNewModuleBase() . ADMIN_FOLDER . '/controllers';
        $aLocations[] = self::getNewModuleBase() . ADMIN_FOLDER . '/views';
        $aLocations[] = self::getNewModuleBase() . ADMIN_FOLDER . '/views/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName);

        # only make folder based on usage
        if (self::getModule()->hasCategories) {
            $aLocations[] = self::getNewModuleBase() . ADMIN_FOLDER . '/views/' . self::getModule()->singleSystemFileName . 'Categories';
        }
        $aLocations[] = self::getNewModuleBase() . '/build';
        $aLocations[] = self::getNewModuleBase() . '/models';

        # only make folder based on usage
        if (self::getModule()->hasUrls || self::getModule()->hasImages) {
            $aLocations[] = self::getNewModuleThemeBase() . '/templates/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName);
        }
        if (self::getModule()->hasUrls) {
            $aLocations[] = self::getNewModuleBase() . '/site/controllers';
            $aLocations[] = self::getNewModuleThemeBase() . '/templates/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName);
            $aLocations[] = self::getNewModuleThemeBase() . '/templates/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName) . '/snippets';
            $aLocations[] = self::getNewModuleThemeBase() . '/templates/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName) . '/views';
        }

        # make directories
        foreach ($aLocations as $sLocation) {
            FileSystem::getOrMakeDirectory($sLocation);
        }

    }

    /**
     * make install
     */
    protected static function generateInstall(): void
    {

        # placeholders
        $sPartial1Content  = '';
        $sPartial2Content  = '';
        $sPartial3Content  = '';
        $sPartial4Content  = '';
        $sPartial5Content  = '';
        $sPartialCategory1 = '';
        $sPartialCategory2 = '';
        $sPartialCategory3 = '';

        if (self::getModule()->hasCategories) {
            $sPartial1Content  .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/adminControllerRoute.txt') . PHP_EOL;
            $sPartial2Content  .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/adminClassRoutes.txt') . PHP_EOL;
            $sPartial3Content  .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/menuCategories.txt') . PHP_EOL;
            $sPartial4Content  .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/hasCategories.txt') . PHP_EOL;
            $sPartialCategory1 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/categoryDefaultTranslations.txt') . PHP_EOL;
            $sPartialCategory2 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/categoryNonDefaultTranslations.txt') . PHP_EOL;
            $sPartialCategory3 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/categoryDefaultSiteTranslations.txt') . PHP_EOL;
        }
        if (self::getModule()->hasUrls) {
            $sPartial4Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/hasUrls.txt') . PHP_EOL;
            $sPartial5Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/hasUrlsSiteTranslations.txt') . PHP_EOL;
        }
        if (self::getModule()->hasVideos) {
            $sPartial4Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/hasVideos.txt') . PHP_EOL;
        }
        if (self::getModule()->hasFiles) {
            $sPartial4Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/hasFiles.txt') . PHP_EOL;
        }
        if (self::getModule()->hasImages) {
            $sPartial4Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/hasImages.txt') . PHP_EOL;
        }
        if (self::getModule()->hasLinks) {
            $sPartial4Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/hasLinks.txt') . PHP_EOL;
        }

        # define replace values and combine code
        $sContent = str_replace(
            self::replaceValues('keys'),
            self::replaceValues('values'),
            str_replace(
                [
                    '{{adminCategoryRoute}}',
                    '{{adminClassRoutes}}',
                    '{{menuCategories}}',
                    '{{code}}',
                    '{{hasUrlsSiteTranslations}}',
                    '{{defaultCategoryTranslations}}',
                    '{{nonDefaultCategoryTranslations}}',
                    '{{defaultSiteCategoryTranslations}}',
                    '{{fontawesomeIcon}}',
                ],
                [$sPartial1Content, $sPartial2Content, $sPartial3Content, $sPartial4Content, $sPartial5Content, $sPartialCategory1, $sPartialCategory2, $sPartialCategory3, self::getModule()->fontawesomeIcon],
                FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/install/template.txt')
            )
        );

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/build/' . self::$aFilesNames['install'], $sContent);

    }

    /**
     * make install
     */
    protected static function generateReadme(): void
    {

        # get content
        $sContent = FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/readme.txt') . PHP_EOL;

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/build/readme.txt', $sContent);

    }

    /**
     * builds module model
     */
    protected static function generateModuleModel(): void
    {

        # placeholders
        $sPartial1Content  = '';
        $sPartial2Content  = '';
        $sPartial3Content  = '';
        $sPartial4Content  = '';
        $sPartial5Content  = '';
        $sPartialCategory1 = '';
        $sPartialCategory2 = '';
        $sPartialCrumbles  = '';

        if (self::getModule()->hasUrls) {
            $sPartial1Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/classUrlProperties.txt') . PHP_EOL;
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/hasUrls.txt') . PHP_EOL;
            $sPartial4Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/hasUrlFunctions.txt') . PHP_EOL;
        }
        if (self::getModule()->hasCategories) {
            $sPartial2Content  .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/hasCategories.txt') . PHP_EOL;
            $sPartial3Content  .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/getSetCategories.txt') . PHP_EOL;
            $sPartialCategory1 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/validateCategory.txt') . PHP_EOL;
            $sPartialCategory2 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/categoryIsOnline.txt') . PHP_EOL;
            $sPartialCrumbles  .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/getCrumblesCategories.txt') . PHP_EOL;
        } else {
            $sPartialCrumbles .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/getCrumbles.txt') . PHP_EOL;
        }
        if (self::getModule()->hasVideos) {
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/hasVideos.txt') . PHP_EOL;
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/getVideoLinks.txt') . PHP_EOL;
        }
        if (self::getModule()->hasFiles) {
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/hasFiles.txt') . PHP_EOL;
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/getFiles.txt') . PHP_EOL;
            $sPartial5Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/classFileConst.txt') . PHP_EOL;
        }
        if (self::getModule()->hasImages) {
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/hasImages.txt') . PHP_EOL;
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/getImages.txt') . PHP_EOL;
        }
        if (self::getModule()->hasLinks) {
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/hasLinks.txt') . PHP_EOL;
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleSnippets/getLinks.txt') . PHP_EOL;
        }

        # define replace values and combine code
        $sContent = str_replace(
            ['{{classUrlProperties}}', '{{classMediaCategoriesProperties}}', '{{hasUrlFunctions}}', '{{getSetCode}}', '{{classFileConst}}', '{{classValidateCategory}}', '{{categoryIsOnline}}', '{{moduleGetCrumbles}}'],
            [$sPartial1Content, $sPartial2Content, $sPartial3Content, $sPartial4Content, $sPartial5Content, $sPartialCategory1, $sPartialCategory2, $sPartialCrumbles],
            FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModule.txt')
        );

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/models/' . StringHelper::toPascalCase(self::getModule()->classFileName) . '.class.php', $sContent);

    }

    /**
     * builds module manager model
     */
    protected static function generateModuleManagerModel(): void
    {

        # placeholders
        $sPartial1Content = '';
        $sPartial2Content = '';
        $sPartial3Content = '';
        $sPartial4Content = '';
        $sPartial5Content = '';
        $sPartial6Content = '';
        $sPartial7Content = '';
        $sPartial8Content = '';

        if (self::getModule()->hasFiles) {
            $sPartial1Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/getSetFileRelation.txt') . PHP_EOL;
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/deleteFiles.txt') . PHP_EOL;
        }
        if (self::getModule()->hasImages) {
            $sPartial1Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/getSetImageRelation.txt') . PHP_EOL;
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/deleteImages.txt') . PHP_EOL;
        }
        if (self::getModule()->hasLinks) {
            $sPartial1Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/getSetLinks.txt') . PHP_EOL;
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/deleteLinks.txt') . PHP_EOL;
        }
        if (self::getModule()->hasVideos) {
            $sPartial1Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/getSetVideo.txt') . PHP_EOL;
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/deleteVideos.txt') . PHP_EOL;
        }
        if (self::getModule()->hasCategories) {
            $sPartial1Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/getSetCategoryRelations.txt') . PHP_EOL;
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/saveCategories.txt') . PHP_EOL;
            $sPartial7Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/categoryGetByFilter1.txt') . PHP_EOL;
            $sPartial8Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/categoryGetByFilter2.txt') . PHP_EOL;
        }
        if (self::getModule()->hasUrls) {
            $sPartial4Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/hasUrlsInsertProperties.txt') . PHP_EOL;
            $sPartial5Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/hasUrlsValuesProperties.txt') . PHP_EOL;
            $sPartial6Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManagerSnippets/hasUrlsUpdateProperties.txt') . PHP_EOL;
        }

        # define replace values and combine code
        $sContent = str_replace(
            ['{{getSetCode}}', '{{deleteCode}}', '{{moduleSaveCategories}}', '{{hasUrlsInsertProperties}}', '{{hasUrlsValuesProperties}}', '{{hasUrlsUpdateProperties}}', '{{manager_category_get_by1}}', '{{manager_category_get_by2}}'],
            [$sPartial1Content, $sPartial2Content, $sPartial3Content, $sPartial4Content, $sPartial5Content, $sPartial6Content, $sPartial7Content, $sPartial8Content],
            FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/module/templateModuleManager.txt')
        );

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/models/' . StringHelper::toPascalCase(self::getModule()->classFileName) . 'Manager.class.php', $sContent);

    }

    /**
     * builds module category model
     */
    protected static function generateModuleCategoryModel(): void
    {

        # get content
        $sContent = FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/category/templateModule.txt') . PHP_EOL;

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/models/' . StringHelper::toPascalCase(self::getModule()->classFileName) . 'Category.class.php', $sContent);

    }

    /**
     * builds module category manager model
     */
    protected static function generateModuleCategoryManagerModel(): void
    {

        # get content
        $sContent = FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/models/category/templateModuleManager.txt') . PHP_EOL;

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/models/' . StringHelper::toPascalCase(self::getModule()->classFileName) . 'CategoryManager.class.php', $sContent);

    }

    /**
     * builds module admin controller
     */
    protected static function generateModuleAdminController(): void
    {

        # placeholders
        $sPartial1Content  = '';
        $sPartial2Content  = '';
        $sPartial3Content  = '';
        $sPartialCategory1 = '';

        if (self::getModule()->hasCategories) {
            $sPartial1Content  .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/admin/templateAdminControllerSnippets/saveCategories.txt') . PHP_EOL;
            $sPartialCategory1 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/admin/templateAdminControllerSnippets/controllerCategoryFilter.txt') . PHP_EOL;
        }
        if (self::getModule()->hasVideos) {
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/admin/templateAdminControllerSnippets/saveVideo.txt') . PHP_EOL;
        }
        if (self::getModule()->hasFiles) {
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/admin/templateAdminControllerSnippets/saveFile.txt') . PHP_EOL;
        }
        if (self::getModule()->hasImages) {
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/admin/templateAdminControllerSnippets/saveImage.txt') . PHP_EOL;
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/admin/templateAdminControllerSnippets/cropImageList.txt') . PHP_EOL;
        }
        if (self::getModule()->hasLinks) {
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/admin/templateAdminControllerSnippets/saveLink.txt') . PHP_EOL;
        }

        # define replace values and combine code
        $sContent = str_replace(
            ['{{saveCategories}}', '{{saveMedia}}', '{{cropImageList}}', '{{controllerCategoryFilter}}'],
            [$sPartial1Content, $sPartial2Content, $sPartial3Content, $sPartialCategory1],
            FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/admin/templateAdminController.txt')
        );

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/admin/controllers/' . self::getModule()->singleSystemFileName . '.cont.php', $sContent);

    }

    /**
     * builds module category admin controller
     */
    protected static function generateModuleCategoryAdminController(): void
    {

        # get content
        $sContent = FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/admin/templateAdminCategoryController.txt') . PHP_EOL;

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/admin/controllers/' . StringHelper::toCamelCase(self::getModule()->controllerFileName) . 'Category.cont.php', $sContent);

    }

    /**
     * builds module admin view - overview
     */
    protected static function generateModuleAdminViewOverview(): void
    {
        # placeholders
        $sPartial1Content = '';

        if (self::getModule()->hasCategories) {
            $sPartial1Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/module/templateAdminDetailSnippets/categoryFilter.txt') . PHP_EOL;
        }

        # define replace values and combine code
        $sContent = str_replace(
            ['{{categoryFilter}}'],
            [$sPartial1Content],
            FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/module/templateAdminOverview.txt') . PHP_EOL
        );

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(
            self::getNewModuleBase() . '/admin/views/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName) . '/' . StringHelper::toCamelCase(self::getModule()->multipleSystemFileName) . '_overview.inc.php',
            $sContent
        );

    }

    /**
     * builds module admin view - detail
     */
    protected static function generateModuleAdminViewDetail(): void
    {

        # placeholders
        $sPartial1Content = '';
        $sPartial2Content = '';
        $sPartial3Content = '';

        if (self::getModule()->hasCategories) {
            $sPartial1Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/module/templateAdminDetailSnippets/hasCategories.txt') . PHP_EOL;
        }
        if (self::getModule()->hasUrls) {
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/module/templateAdminDetailSnippets/hasUrls.txt') . PHP_EOL;
        }
        if (self::getModule()->hasVideos) {
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/module/templateAdminDetailSnippets/hasVideos.txt') . PHP_EOL;
        }
        if (self::getModule()->hasFiles) {
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/module/templateAdminDetailSnippets/hasFiles.txt') . PHP_EOL;
        }
        if (self::getModule()->hasImages) {
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/module/templateAdminDetailSnippets/hasImages.txt') . PHP_EOL;
        }
        if (self::getModule()->hasLinks) {
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/module/templateAdminDetailSnippets/hasLinks.txt') . PHP_EOL;
        }

        # get content
        $sContent = str_replace(
            ['{{hasCategories}}', '{{hasUrls}}', '{{hasMedia}}'],
            [$sPartial1Content, $sPartial2Content, $sPartial3Content],
            FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/module/templateAdminDetail.txt')
        );

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/admin/views/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName) . '/' . StringHelper::toCamelCase(self::getModule()->singleSystemFileName) . '_form.inc.php', $sContent);

    }

    /**
     * builds module admin view - change order
     */
    protected static function generateModuleAdminViewChangeOrder(): void
    {

        # get content
        $sContent = FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/module/templateAdminChangeOrder.txt') . PHP_EOL;

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(
            self::getNewModuleBase() . '/admin/views/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName) . '/' . StringHelper::toCamelCase(self::getModule()->multipleSystemFileName) . '_change_order.inc.php',
            $sContent
        );

    }

    /**
     * builds module category admin view - overview
     */
    protected static function generateModuleCategoryAdminViewOverview(): void
    {

        # get content
        $sContent = FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/category/templateAdminCategoriesOverview.txt') . PHP_EOL;

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/admin/views/' . self::getModule()->singleSystemFileName . 'Categories/' . StringHelper::toCamelCase(self::getModule()->singleSystemFileName) . 'Categories_overview.inc.php', $sContent);

    }

    /**
     * builds module category admin view - detail
     */
    protected static function generateModuleCategoryAdminViewDetail(): void
    {

        # placeholders
        $sPartial1Content = '';

        if (self::getModule()->hasUrls) {
            $sPartial1Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/category/templateAdminCategoryFormSnippets/hasUrls.txt') . PHP_EOL;
        }

        # get content
        $sContent = str_replace(
            ['{{hasUrls}}'],
            [$sPartial1Content],
            FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/category/templateAdminCategoryForm.txt')
        );

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/admin/views/' . self::getModule()->singleSystemFileName . 'Categories/' . StringHelper::toCamelCase(self::getModule()->singleSystemFileName) . 'Category_form.inc.php', $sContent);

    }

    /**
     * builds module category admin view - change order
     */
    protected static function generateModuleCategoryAdminViewChangeOrder(): void
    {

        # get content
        $sContent = FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/admin/category/templateCategoriesChangeOrder.txt') . PHP_EOL;

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(
            self::getNewModuleBase() . '/admin/views/' . self::getModule()->singleSystemFileName . 'Categories/' . StringHelper::toCamelCase(self::getModule()->singleSystemFileName) . 'Categories_change_order.inc.php',
            $sContent
        );

    }

    /**
     * builds module FE controller
     */
    protected static function generateModuleController(): void
    {

        # placeholders
        $sPartial1Content  = '';
        $sPartial2Content  = '';
        $sPartial3Content  = '';
        $sPartial4Content  = '';
        $sPartialCategory1 = '';
        $sPartialCategory2 = '';
        $sPartialCategory3 = '';
        $sPartialCategory4 = '';
        $sPartialCategory5 = '';

        if (self::getModule()->hasCategories) {
            $sPartial1Content  .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/hasCategories.txt') . PHP_EOL;
            $sPartial2Content  .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/functionCategory.txt') . PHP_EOL;
            $sPartialCategory1 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/controllerCategoryIndex.txt') . PHP_EOL;
            $sPartialCategory2 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/controllerCategoryPage1.txt') . PHP_EOL;
            $sPartialCategory3 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/controllerCategoryPage2.txt') . PHP_EOL;
            $sPartialCategory4 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/controllerCategoryPage3.txt') . PHP_EOL;
            $sPartialCategory5 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/controllerOverviewCategory.txt') . PHP_EOL;
        } else {
            $sPartialCategory4 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/controllerNotCategoryPage3.txt') . PHP_EOL;
            $sPartialCategory5 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/controllerOverview.txt') . PHP_EOL;
        }
        if (self::getModule()->hasImages) {
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/hasImages.txt') . PHP_EOL;
            $sPartial4Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/controllerSetVariablesImages.txt') . PHP_EOL;
        }
        if (self::getModule()->hasFiles) {
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/hasFiles.txt') . PHP_EOL;
            $sPartial4Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/controllerSetVariablesFiles.txt') . PHP_EOL;
        }
        if (self::getModule()->hasLinks) {
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/hasLinks.txt') . PHP_EOL;
            $sPartial4Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/controllerSetVariablesLinks.txt') . PHP_EOL;
        }
        if (self::getModule()->hasVideos) {
            $sPartial3Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/hasVideos.txt') . PHP_EOL;
            $sPartial4Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateControllerSnippets/controllerSetVariablesVideos.txt') . PHP_EOL;
        }

        # get content
        $sContent = str_replace(
            [
                '{{hasCategories}}',
                '{{functionCategory}}',
                '{{hasMedia}}',
                '{{setRenderVariables}}',
                '{{controllerCategoryIndex}}',
                '{{controllerCategoryPage1}}',
                '{{controllerCategoryPage2}}',
                '{{controllerCategoryPage3}}',
                '{{controllerOverview}}',
            ],
            [$sPartial1Content, $sPartial2Content, $sPartial3Content, $sPartial4Content, $sPartialCategory1, $sPartialCategory2, $sPartialCategory3, $sPartialCategory4, $sPartialCategory5],
            FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/controllers/frontend/templateController.txt')
        );

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleBase() . '/site/controllers/' . StringHelper::toPascalCase(self::getModule()->controllerFileName) . 'Controller.php', $sContent);

    }

    /**
     * make module FE view - detail
     */
    protected static function generateModuleViewDetail(): void
    {

        # get content
        $sContent = FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/frontend/templateDetail.txt') . PHP_EOL;

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleThemeBase() . '/templates/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName) . '/views/' . self::getModule()->singleSystemFileName . '_details.inc.php', $sContent);

    }

    /**
     * make module FE view - overview
     */
    protected static function generateModuleViewOverview(): void
    {

        # placeholders
        $sPartial1Content  = '';
        $sPartial2Content  = '';
        $sPartialCategory1 = '';

        if (self::getModule()->hasImages) {
            $sPartial1Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/frontend/snippets/getImages.txt') . PHP_EOL;
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/frontend/snippets/writeImage.txt') . PHP_EOL;
        }
        if (self::getModule()->hasCategories) {
            $sPartialCategory1 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/frontend/snippets/categoryBasedClass.txt') . PHP_EOL;
        } else {
            $sPartialCategory1 .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/frontend/snippets/notCategoryBasedClass.txt') . PHP_EOL;
        }

        # get content
        $sContent = str_replace(
            ['{{getImages}}', '{{writeImage}}', '{{categoryBasedClass}}'],
            [$sPartial1Content, $sPartial2Content, $sPartialCategory1],
            FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/frontend/templateOverview.txt')
        );

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleThemeBase() . '/templates/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName) . '/views/' . self::getModule()->multipleSystemFileName . '_overview.inc.php', $sContent);

    }

    /**
     * make module FE view - category
     */
    protected static function generateModuleViewCategory(): void
    {

        # placeholders
        $sPartial1Content = '';
        $sPartial2Content = '';

        if (self::getModule()->hasImages) {
            $sPartial1Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/frontend/snippets/getImages.txt') . PHP_EOL;
            $sPartial2Content .= FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/frontend/snippets/writeImage.txt') . PHP_EOL;
        }

        # get content
        $sContent = str_replace(
            ['{{getImages}}', '{{writeImage}}'],
            [$sPartial1Content, $sPartial2Content],
            FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/frontend/templateCategory.txt')
        );

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleThemeBase() . '/templates/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName) . '/views/' . self::getModule()->singleSystemFileName . 'Category_overview.inc.php', $sContent);

    }

    /**
     * make module FE snippet - navigationsub
     */
    protected static function generateModuleSnippetNavigationSub(): void
    {

        # placeholders
        $sContent = '';

        # get content
        if (self::getModule()->hasCategories) {
            $sContent = FileSystem::read(SYSTEM_MODULES_FOLDER . self::$buildComponentPath . '/views/frontend/snippets/navigationSub.txt') . PHP_EOL;
        }

        # replace tags
        $sContent = str_replace(self::replaceValues('keys'), self::replaceValues('values'), $sContent);

        # write new file
        FileSystem::write(self::getNewModuleThemeBase() . '/templates/' . StringHelper::toCamelCase(self::getModule()->moduleFolderName) . '/snippets/navigationSub.inc.php', $sContent);

    }

    /**
     * defines replace values
     *
     * @param $sFilter filter certain part of data
     *
     * @return array
     */
    protected static function replaceValues($sFilter): array
    {

        $aReplaceValues = [
            "{{defaultLocaleItemName}}"                       => self::getModule()->defaultLocaleTranslationItem,
            "{{notDefaultLocaleItemName}}"                    => self::getModule()->notDefaultLocaleTranslationItem,
            "{{defaultLocaleItemNameMultiple}}"               => self::getModule()->defaultLocaleTranslationItems,
            "{{notDefaultLocaleItemNameMultiple}}"            => self::getModule()->notDefaultLocaleTranslationItems,
            "{{defaultLocaleItemNameCapitalized}}"            => ucfirst(self::getModule()->defaultLocaleTranslationItem),
            "{{notDefaultLocaleItemNameCapitalized}}"         => ucfirst(self::getModule()->notDefaultLocaleTranslationItem),
            "{{defaultLocaleItemNameLowercase}}"              => strtolower(self::getModule()->defaultLocaleTranslationItem),
            "{{notDefaultLocaleItemNameLowercase}}"           => strtolower(self::getModule()->notDefaultLocaleTranslationItem),
            "{{defaultLocaleItemNameMultipleCapitalized}}"    => ucfirst(self::getModule()->defaultLocaleTranslationItems),
            "{{notDefaultLocaleItemNameMultipleCapitalized}}" => ucfirst(self::getModule()->notDefaultLocaleTranslationItems),
            "{{defaultLocaleItemNameMultipleLowercase}}"      => strtolower(self::getModule()->defaultLocaleTranslationItems),
            "{{notDefaultLocaleItemNameMultipleLowercase}}"   => strtolower(self::getModule()->notDefaultLocaleTranslationItems),
            "{{moduleFolderName}}"                            => StringHelper::toCamelCase(self::getModule()->moduleFolderName),
            "{{classFileName}}"                               => StringHelper::toPascalCase(self::getModule()->classFileName),
            "{{controllerRoute}}"                             => prettyUrlPart(self::getModule()->moduleFolderName),
            "{{controllerFileName}}"                          => StringHelper::toCamelCase(self::getModule()->controllerFileName),
            "{{controllerFileNamePascalCased}}"               => StringHelper::toPascalCase(self::getModule()->controllerFileName),
            "{{singleSystemFileName}}"                        => StringHelper::toCamelCase(self::getModule()->singleSystemFileName),
            "{{multipleSystemFileName}}"                      => StringHelper::toCamelCase(self::getModule()->multipleSystemFileName),
            "{{hasFiles}}"                                    => self::getModule()->hasFiles,
            "{{hasVideos}}"                                   => self::getModule()->hasVideos,
            "{{hasImages}}"                                   => self::getModule()->hasImages,
            "{{hasLinks}}"                                    => self::getModule()->hasLinks,
            "{{hasCategories}}"                               => self::getModule()->hasCategories,
            "{{hasUrls}}"                                     => self::getModule()->hasUrls,
            "{{tableName}}"                                   => StringHelper::toSnakeCase(self::getModule()->moduleFolderName),
            "{{relationTableNamePrefix}}"                     => StringHelper::toSnakeCase(self::getModule()->relationTableNamePrefix),
            "{{databaseAlias}}"                               => strtolower(StringHelper::getCapitals(StringHelper::toPascalCase((self::getModule()->classFileName)))),
            "{{idName}}"                                      => StringHelper::toCamelCase(self::getModule()->classFileName),
            "{{pageSystemName}}"                              => strtolower(self::getModule()->pageSystemName),
            "{{defaultLocalePageTitle}}"                      => self::getModule()->defaultLocalePageTitle,
            "{{defaultLocalePageControllerRoute}}"            => StringHelper::toSlug(self::getModule()->defaultLocalePageControllerRoute),
            "{{notDefaultLocalePageControllerRoute}}"         => StringHelper::toSlug(self::getModule()->notDefaultLocalePageControllerRoute),
            "{{notDefaultLocalePageTitle}}"                   => self::getModule()->notDefaultLocalePageTitle,
            "{{moduleDescription}}"                           => self::getModule()->moduleDescription,
            "{{adminUrlPath}}"                                => StringHelper::toSlug(self::getModule()->moduleFolderName),
        ];

        switch ($sFilter) {
            case 'keys':
                return array_keys($aReplaceValues);
                break;
            case 'values':
                return array_values($aReplaceValues);
                break;
            default:
                return $aReplaceValues;
                break;
        }

    }

}