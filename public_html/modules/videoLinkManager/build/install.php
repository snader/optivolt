<?php

// check folders existance and writing rights
$aCheckRightFolders = [
];

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'videoLinkManagement' => [
        'module'     => 'videoLinkManager',
        'controller' => 'videoLinkManagement',
    ],
];

$aNeededClassRoutes = [
    'VideoLink'            => [
        'module' => 'videoLinkManager',
    ],
    'VideoLinkManager'     => [
        'module' => 'videoLinkManager',
    ],
    'VideoLinkManagerHTML' => [
        'module' => 'videoLinkManager',
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'global_videolinks', 'text' => 'Videolinks'],
        ['label' => 'global_videolinks_info', 'text' => 'Ondersteunde videolinks: Youtube, Vimeo'],
        ['label' => 'global_videolinks_limit', 'text' => 'video(s) is bereikt'],
        ['label' => 'global_no_videolinks_uploaded', 'text' => 'Er zijn nog geen Videolinks geüpload'],
        ['label' => 'global_max_videolinks', 'text' => 'Videolinks uploaden'],
        ['label' => 'global_video_save', 'text' => 'Videolink toevoegen'],
        ['label' => 'global_video_links_already_added', 'text' => 'Reeds toegevoegde Videolinks'],
        ['label' => 'global_video_drag', 'text' => 'Sleep de Videolinks om de volgorde aan te passen'],
        ['label' => 'global_change_video_link', 'text' => 'Video titel wijzigen'],
        ['label' => 'global_save_video_link', 'text' => 'Videolink opslaan'],
        ['label' => 'global_video_title_tooltip', 'text' => 'Vul een titel voor de Video in'],
        ['label' => 'global_video_url_tooltip', 'text' => 'Vul een URL voor de Video in'],
        ['label' => 'js_video_order_changed', 'text' => 'Videolinks volgorde gewijzigd'],
        ['label' => 'js_video_not_changed', 'text' => 'Video link kon niet worden gewijzigd'],
        ['label' => 'js_video_saved', 'text' => 'Video link opgeslagen'],
        ['label' => 'js_video_deleted', 'text' => 'Video link succesvol verwijderd'],
        ['label' => 'js_video_not_deleted', 'text' => 'Video link kon niet worden verwijderd'],
    ],
    'en' => [
        ['label' => 'global_videolinks', 'text' => 'Videolinks'],
        ['label' => 'global_videolinks_info', 'text' => 'Supported videolinks: Youtube, Vimeo'],
        ['label' => 'global_videolinks_limit', 'text' => 'video(s) has been reached'],
        ['label' => 'global_no_videolinks_uploaded', 'text' => 'There are no Videolinks uploaded'],
        ['label' => 'global_max_videolinks', 'text' => 'Videolinks uploaded'],
        ['label' => 'global_video_save', 'text' => 'Add Videolink'],
        ['label' => 'global_video_links_already_added', 'text' => 'Previously added Videolinks'],
        ['label' => 'global_video_drag', 'text' => 'Drag and drop the Videolinks to change the order'],
        ['label' => 'global_change_video_link', 'text' => 'Change Video title'],
        ['label' => 'global_save_video_link', 'text' => 'Save Videolink'],
        ['label' => 'global_video_title_tooltip', 'text' => 'Please, fill in a title for the Video'],
        ['label' => 'global_video_url_tooltip', 'text' => 'Please, fill in a URL for the Video'],
        ['label' => 'js_video_order_changed', 'text' => 'Video links reordered'],
        ['label' => 'js_video_not_changed', 'text' => 'Video link cannot be changed'],
        ['label' => 'js_video_saved', 'text' => 'Video link saved'],
        ['label' => 'js_video_deleted', 'text' => 'Video link successfully removed'],
        ['label' => 'js_video_not_deleted', 'text' => 'Video link cannot be deleted'],
    ],
];

$aNeededSiteControllerRoutes = [
];
