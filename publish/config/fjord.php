<?php

return [

    'resource_path' => 'fjord',

    'route_prefix' => 'admin',

    'mediaconversions' => [
        'repeatables' => [
            'sm' => [300, 300, 8],
            'md' => [500, 500, 3],
            'lg' => [900, 900, 2],
            'xl' => [1400, 1400, 1]
        ]
    ],

    'pages' => [

        // Should Pages be translatable by default.
        'translatable' => true
    ],

    'crud' => [

    ]
];
