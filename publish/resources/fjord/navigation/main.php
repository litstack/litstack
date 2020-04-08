<?php

// Type artisan command fjord:nav to see all navEntry presets.

return [
    [
        'Collections',
        [
            'title' => ucfirst(__f('fj.pages')),
            'icon' => '<i class="fas fa-file"></i>',
            'children' => [
                fjord()->navEntry('pages.home', [
                    'icon' => '<i class="fas fa-home">'
                ]),
            ],
        ],
    ],
];
