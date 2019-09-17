<?php

return [

    'route_prefix' => 'admin',

    'resource_path' => 'fjord',

    'navigation_path' => 'navigation',


    // Layouts: horizontal / vertical
    'layout' => env('FJORD_LAYOUT', 'vertical'),

    'default_route' => 'pages/home',

    'assets' => [
        // Set path to the app.js file.
        'js' => null,
        'css' => [
            // Put path to css files that should be included here...
        ],
    ],

    'mediaconversions' => [
        'default' => [
            'sm' => [300, 300, 8],
            'md' => [500, 500, 3],
            'lg' => [900, 900, 2],
            'xl' => [1400, 1400, 1]
        ]
    ],

    'forms' => [
        'pages' => [
            // Should Pages be translatable by default.
            'translatable' => true,
            'route_prefix' => 'pages'
        ],
        'settings' => [
            'translatable' => false,
        ],
        'collections' => [

        ]
    ],

    'crud' => [
        'preview' => [
            // devices: mobile / tablet / desktop
            'default_device' => 'desktop'
        ]
    ]
];
