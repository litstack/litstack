<?php



return [
    'form_fields' => [
        [
            'translatable' => true,
            'id' => 'h1',
            'type' => 'input',
            'title' => 'Headline',
            'placeholder' => 'Headline',
            'hint' => 'The Headline needs to be filled',
            'width' => 12
        ],
        [
            'id' => 'content_block',
            'type' => 'block',
            'title' => 'Content',
            'hint' => 'The Headline needs to be filled',
            'width' => 12,
            'repeatables' => [
                'text', 'image'
            ]
        ]
    ]
];
