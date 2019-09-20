<?php

//use App\Models\Post;

return [
    'cards' => [
        [
            'title' => 'Homepage',
            'text' =>  'Edit the content of your Home-Page',
            'links' => [
                [
                    'text' => '<i class="fas fa-edit"></i> Edit',
                    'class' => 'btn btn-secondary btn-sm',
                    'target' => '/admin/pages/home'
                ]
            ]
        ],
        /*
        [
            'title' => 'Posts',
            'text' =>  'You have currently  ' . \App\Models\Post::all()->count() . ' Posts.',
            'links' => [
                [
                    'text' => '<i class="fas fa-list"></i> Show List',
                    'class' => 'btn btn-secondary btn-sm',
                    'target' => '/admin/posts'
                ],
                [
                    'text' => '<i class="fas fa-plus"></i> Add Post',
                    'class' => 'btn btn-secondary btn-sm',
                    'target' => '/admin/posts/create'
                ]
            ]
        ],
        */
    ]
];
