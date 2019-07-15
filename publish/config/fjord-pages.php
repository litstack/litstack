<?php

return [
    'home' =>[
        'translatable' => true,
        'fields' => [
            [
                'id' => 'h1',
                'type' => 'input',
                'title' => 'Headline',
                'placeholder' => 'Headline',
                'hint' => 'The Headline neeeds to be filled',
                'width' => 8
            ],
            [
                'id' => 'h2',
                'type' => 'input',
                'title' => 'SubHeadline',
                'placeholder' => 'Headline',
                'hint' => 'The SubHeadline neeeds to be filled',
                'width' => 5
            ],
            [
                'id' => 'intro',
                'type' => 'wysiwyg',
                'title' => 'Intro Text',
                'placeholder' => 'Intro',
                'hint' => 'The SubHeadline neeeds to be filled',
                'width' => 12
            ],
            [
                'id' => 'contentblock',
                'type' => 'block',
                'title' => 'Content',
                'placeholder' => 'Headline',
                'hint' => 'The Headline neeeds to be filled',
                'width' => 12,
                'repeatables' => [
                    'article', 'image'
                ]
            ]
        ]
    ],
];
