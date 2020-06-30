<?php

return [
    "last_edited" => "Last edited <b>:time</b> by <b>:user</b>",
    'fields' => [
        'blocks' => [
            'expand' => 'expand',
            'expand_all' => 'expand all',
            'collapse_all' => 'collapse all'
        ],
        'relation' => [
            'goto' => 'Go to relation',
            'unlink' => 'Unlink relation',
            'edit' => 'Edit relation',
            'messages' => [
                'confirm_unlink' => 'Please confirm that you wish to unlink the item.',
            ]
        ],
        'wysiwyg' => [
            'new_window' => 'open in new window'
        ],
        'list' => [
            'messages' => [
                'max_depth' => 'The list can be nested a maximum of :count levels.',
                'confirm_delete' => 'Should :item item really be deleted?',
                'confirm_delete_info' => 'If you remove this item, you also remove all child items below it.',
            ]
        ]
    ],
    'meta' => [
        'title_hint' => "Easily understandable meaningful sentence. Gives an idea what the page content is about. Maximum :width wide.",
        'description_hint' => "Short but meaningful summary of the page. Contains the most important keywords of the page content.",
    ]
];
