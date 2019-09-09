<?php

return [
    'blog' => [
        'view0' => [
            'order' => 1,
            'view'  => 'recipe::view.name.2'
        ],
        'view1' => [
            'order' => 0,
            'view'  => 'recipe::view.name'
        ],
    ],
    'other-module' => [
        'view1' => [
            'order' => 0,
            'view'  => 'recipe::view.name'
        ],
        'grouped' => [
            'item1' => [
                'order' => 0,
                'view'  => 'recipe::view.name'
            ]
        ]
    ]
];
