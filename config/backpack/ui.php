<?php


return [


    'view_namespace' => 'backpack.theme-tabler::',
    'view_namespace_fallback' => 'backpack.theme-tabler::',
    'default_date_format' => 'D MMM YYYY',
    'default_datetime_format' => 'D MMM YYYY, HH:mm',
    'html_direction' => 'ltr',
    // ----
    // HEAD
    // ----
    // Project name - shown in the window title
    'project_name' => 'Telecripto',
    // Content of the HTML meta robots tag to prevent indexing and link following
    'meta_robots_content' => 'noindex, nofollow',
    'home_link' => '/',
    'project_logo' => '<center><span class="system">TELECRIPTO</span></center>',
    // Show / hide breadcrumbs on admin panel pages.
    'breadcrumbs' => true,
    // Developer or company name. Shown in footer.
    'developer_name' => 'Rodolfo Gonzalez',
    // Developer website. Link in footer. Type false if you want to hide it.
    'developer_link' => 'https://superlocales.com',
    // Show powered by Laravel Backpack in the footer? true/false
    'show_powered_by' => false,
    'show_getting_started' => false ,
    'styles' => [

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
         'resources/css/app.css',
         //'resources/js/app.js',
    ],
    // JS files that are loaded in all pages, using Laravel's asset() helper
    'scripts' => [

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
