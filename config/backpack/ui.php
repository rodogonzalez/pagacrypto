<?php


return [


    'view_namespace' => 'backpack.theme-tabler::',
    'view_namespace_fallback' => 'backpack.theme-tabler::',

    // Date & Datetime Format Syntax: https://carbon.nesbot.com/docs/#api-localization
    'default_date_format' => 'D MMM YYYY',
    'default_datetime_format' => 'D MMM YYYY, HH:mm',

    // Direction, according to language
    // (left-to-right vs right-to-left)
    'html_direction' => 'ltr',

    // ----
    // HEAD
    // ----

    // Project name - shown in the window title
    'project_name' => 'Super Locales',

    // Content of the HTML meta robots tag to prevent indexing and link following
    'meta_robots_content' => 'noindex, nofollow',

    // ------
    // HEADER
    // ------

    // When clicking on the admin panel's top-left logo/name,
    // where should the user be redirected?
    // The string below will be passed through the url() helper.
    // - default: '' (project root)
    // - alternative: 'admin' (the admin's dashboard)
    'home_link' => '/',

    // Menu logo. You can replace this with an <img> tag if you have a logo.
    'project_logo' => '<center><span class="sl_logo"></span></center>',

    // Show / hide breadcrumbs on admin panel pages.
    'breadcrumbs' => true,

    // ------
    // FOOTER
    // ------

    // Developer or company name. Shown in footer.
    'developer_name' => 'Rodolfo Gonzalez',

    // Developer website. Link in footer. Type false if you want to hide it.
    'developer_link' => 'https://superlocales.com',

    // Show powered by Laravel Backpack in the footer? true/false
    'show_powered_by' => false,

    // ---------
    // DASHBOARD
    // ---------

    // Show "Getting Started with Backpack" info block?
    'show_getting_started' => false ,//env('APP_ENV') == 'local',

    // -------------
    // GLOBAL STYLES
    // -------------

    // CSS files that are loaded in all pages, using Laravel's asset() helper
    'styles' => [
        '/js/leaflet/dist/leaflet.css',

        //'/build/main.css'
        // 'styles/example.css',
        // 'https://some-cdn.com/example.css',
    ],

    // CSS files that are loaded in all pages, using Laravel's mix() helper
    'mix_styles' => [ // file_path => manifest_directory_path
        // 'css/app.css' => '',

    ],

    // CSS files that are loaded in all pages, using Laravel's @vite() helper
    // Please note that support for Vite was added in Laravel 9.19. Earlier versions are not able to use this feature.
    'vite_styles' => [ // resource file_path
         //'resources/css/app.css',
         //'resources/js/app.js',
    ],

    // --------------
    // GLOBAL SCRIPTS
    // --------------

    // JS files that are loaded in all pages, using Laravel's asset() helper
    'scripts' => [
        '/js/quagga/dist/quagga.min.js',
        '/js/leaflet/dist/leaflet.js',
        '/js/admin.js',
        // 'js/example.js',
        // 'https://unpkg.com/vue@2.4.4/dist/vue.min.js',
        // 'https://unpkg.com/react@16/umd/react.production.min.js',
        // 'https://unpkg.com/react-dom@16/umd/react-dom.production.min.js',
    ],

    // JS files that are loaded in all pages, using Laravel's mix() helper
    'mix_scripts' => [ // file_path => manifest_directory_path
        // 'js/app.js' => '',

    ],

    // JS files that are loaded in all pages, using Laravel's @vite() helper
    'vite_scripts' => [ // resource file_path
         //'resources/js/admin.js',

    ],

    'classes' => [
        /**
         * Use this as fallback config for themes to pass classes to the table displayed in List Operation
         * It defaults to: "table table-striped table-hover nowrap rounded card-table table-vcenter card-table shadow-xs border-xs".
         */
        'table' => null,

        /**
         * Use this as fallback config for themes to pass classes to the table wrapper component displayed in List Operation.
         */
        'tableWrapper' => null,
    ],

];
