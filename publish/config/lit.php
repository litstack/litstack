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

    'route_prefix' => 'admin',

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
    ],
];
