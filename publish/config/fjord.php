<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fjord Route-Prefix
    |--------------------------------------------------------------------------
    |
    | This option controls under which route-prefix the fjord admin-interface
    | will be located, e.g. http://your-domain.tld/admin.
    |
    */

    'route_prefix' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Fjord Default-Route
    |--------------------------------------------------------------------------
    |
    | Set the default route a user is redirected to after logging in.
    |
    */

    'default_route' => '/',

    /*
    |--------------------------------------------------------------------------
    | Fjord Login
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
    | Set the Google Analytics Id to track traffic on your Fjord application.
    |
    */

    'google-analytics-id' => env('GOOGLE_ANALYTICS_ID', false),

    /*
    |--------------------------------------------------------------------------
    | Fjord Translatable
    |--------------------------------------------------------------------------
    |
    | Set the default route a user is redirected to after logging in.
    |
    */

    'translatable' => [

        'locale' => 'en',

        'translatable' => true,

        'locales' => [
            'en',
            'de',
        ],

        'fallback_locale' => 'en',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fjord Assets
    |--------------------------------------------------------------------------
    |
    | You may add own assets to the fjord-interface. To use an own version of
    | the app.js follow the instructions in the documentation.
    | CSS-Files may be added to the app in order to style your forms
    | to your liking.
    |
    */

    'assets' => [
        // Set path to the app.js file.
        'js'  => null,
        'css' => [
            // Put path to css files that should be included here...
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
    | Fjord CRUD
    |--------------------------------------------------------------------------
    |
    | The default CRUD-settings
    |
    */

    'crud' => [

        'preview' => [
            // devices: mobile / tablet / desktop
            'default_device' => 'desktop',
        ],
    ],
];
