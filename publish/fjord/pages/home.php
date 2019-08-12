<?php

return [
    'fields' => [
        [
            'translatable' => true,
            'id' => 'h1',
            'type' => 'input',
            'title' => 'Headline',
            'placeholder' => 'Headline',
            'hint' => 'The Headline neeeds to be filled',
            'width' => 8
        ],
        [
            'translatable' => true,
            'id' => 'h2',
            'type' => 'input',
            'title' => 'SubHeadline',
            'placeholder' => 'Headline',
            'hint' => 'The SubHeadline neeeds to be filled',
            'width' => 5
        ],
        [
            'translatable' => true,
            'id' => 'intro',
            'type' => 'wysiwyg',
            'title' => 'Intro Text',
            'placeholder' => 'Intro',
            'hint' => 'The SubHeadline neeeds to be filled',
            'width' => 12
        ],
        [
            'translatable' => true,
            'id' => 'contentblock',
            'type' => 'block',
            'title' => 'Content',
            'hint' => 'Add content to your page',
            'width' => 12,
            'repeatables' => [
                'article', 'image'
            ]
        ]
    ]
];
