<?php

return [
    'article' => [
        [
            'id' => 'title',
            'type' => 'input',
            'title' => 'Title',
            'placeholder' => 'Title',
            'hint' => 'The title neeeds to be filled',
            'width' => 6
        ],
        [
            'id' => 'text',
            'type' => 'wysiwyg',
            'title' => 'Text',
            'placeholder' => 'Link',
            'hint' => 'Lorem',
            'width' => 6
        ]
    ],
    'image' => [
        [
            'type' => 'image',
            'id' => 'image',
            'title' => 'Image',
            'hint' => 'Lorem ipsum',
            'width' => 12,
            'maxFiles' => 3,
            'model' => 'Post'
        ],
    ]
];
