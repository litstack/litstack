<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Lit Route-Prefix
    |--------------------------------------------------------------------------
    |
    | This option controls under which route-prefix the lit admin-interface
    | will be located, e.g. http://your-domain.tld/admin.
    |
    */

    'route_prefix' => env('LITSTACK_ROUTE_PREFIX', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Lit Domain
    |--------------------------------------------------------------------------
    |
    | You may wish do make the litstack admin panel accessible form a certain
    | domain, e.g. http://admin.your-domain.tld
    |
    */

    'domain' => env('LITSTACK_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Lit Default-Route
    |--------------------------------------------------------------------------
    |
    | Set the default route a user is redirected to after logging in.
    |
    */

    'default_route' => '/',

    /*
    |--------------------------------------------------------------------------
    | Lit Guard
    |--------------------------------------------------------------------------
    |
    | The guard that is used to authenticate users that are allowed into the
    | litstack application.
    |
    */

    'guard' => 'lit',

    /*
    |--------------------------------------------------------------------------
    | Lit Login
    |--------------------------------------------------------------------------
    |
    | Set login.username to true to allow logging in using username or email.
    |
    */

    'login' => [
        'username' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Litstack Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded  if litstack is installed.
    | Feel free to remove services if you don't need them.
    |
    */

    'providers' => [
        \Ignite\Config\ConfigServiceProvider::class,
        \Ignite\Translation\TranslationServiceProvider::class,
        \Ignite\Permissions\PermissionsServiceProvider::class,
        \Ignite\Vue\VueServiceProvider::class,
        \Ignite\Chart\ChartServiceProvider::class,
        \Ignite\Crud\CrudServiceProvider::class,
        \Ignite\User\UserServiceProvider::class,
        \Ignite\Page\PageServiceProvider::class,
        \Ignite\Search\SearchServiceProvider::class,
        //\Ignite\Auth\PasswordResetServiceProvider::class,

        // Uncomment to enable a link to your system info in the topbar navigation.
        //\Ignite\Info\InfoServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Analytics Id
    |--------------------------------------------------------------------------
    |
    | Set the Google Analytics Id to track traffic on your Lit application.
    |
    */

    'google-analytics-id' => env('GOOGLE_ANALYTICS_ID', false),

    /*
    |--------------------------------------------------------------------------
    | Lit Translatable
    |--------------------------------------------------------------------------
    |
    | Set the default route a user is redirected to after logging in.
    |
    */

    'translatable' => [

        'locale' => 'en',

        'translatable' => true,

        /**
         * The languages in which your litstack application should be displayed.
         */
        'locales' => [
            'en',
            'de',
            'fr',
            'fa',
            'pl',
            'it',
            'tr',
            'es',
        ],

        'fallback_locale' => 'en',
    ],

    /*
    |--------------------------------------------------------------------------
    | Lit Assets
    |--------------------------------------------------------------------------
    |
    | You may add own assets to the lit-interface. To use an own version of
    | the app.js follow the instructions in the documentation.
    | CSS-Files may be added to the app in order to style your forms
    | to your liking.
    |
    */

    'assets' => [
        // Set path to the main app.js file.
        'app_js' => null,

        'scripts' => [
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/js/all.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.0.2/addons/cleave-phone.de.js',
            'https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.0.2/addons/cleave-phone.us.js',
        ],
        'styles' => [
            //
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Media-Conversions
    |--------------------------------------------------------------------------
    |
    | These settings are used for converting your uploaded images. If your
    | CRUD-Models use the HasMediaTrait, they should have a function
    | registerMediaConversions in which the conversions are set up.
    |
    | Read the full documentation at
    | https://docs.spatie.be/laravel-medialibrary/v7/converting-images/defining-conversions/
    |
    */

    'mediaconversions' => [
        'default' => [
            'sm' => [300, 300, 8],
            'md' => [500, 500, 3],
            'lg' => [900, 900, 2],
            'xl' => [1400, 1400, 1],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Lit CRUD
    |--------------------------------------------------------------------------
    |
    | The default CRUD-settings
    |
    */

    'crud' => [

        'preview' => [
            /**
             * Available devices: mobile / tablet / desktop.
             */
            'default_device' => 'desktop',
        ],

        // Indicates how long forms are cached (in minutes). The form cache
        // cleared everytime a form gets updated.
        'form_ttl' => 60 * 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | Lit Field-Settings
    |--------------------------------------------------------------------------
    |
    | The customizable settings for fields
    |
    */

    'fields' => [
        /**
         * Configuration for the WYSIWYG-Editor.
         */
        'wysiwyg' => [
            /**
             * The Headline-Levels that can be set.
             */
            'headingLevels' => [2, 3, 4],
            /**
             * Controls that should be shown in the WYSIWYG-Editor.
             */
            'controls' => [
                'format',
                'bold',
                'italic',
                'strike',
                'underline',
                'bullet_list',
                'ordered_list',
                'blockquote',
                'href',
                'colors',
                'table',
            ],
            /**
             * Font colors to chose from.
             */
            'colors' => ['#4951f2', '#f67693', '#f6ed76', '#9ff2ae', '#83c2ff', '#70859c'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Lit Migrations path
    |--------------------------------------------------------------------------
    |
    | You may configure a special path from which your Litstack migrations
    | are loaded, for example when recreating user permissions.
    |
    */

    'migrations' => [
        'path' => database_path('migrations'),
    ],
];
