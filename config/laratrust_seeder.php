<?php

return [
    'role_structure' => [
        'superadministrator' => [
            'users' => 'c,r,u,d',
            'acl' => 'c,r,u,d',
            'profile' => 'c,r,u,d',
            'inventory' => 'c,r,u,d',
            'discounts' => 'c,r,u,d',
            'accounts' => 'c,r,u,d',
            'reporting' => 'c,r,u,d',
            'godmode' => 'c,r,u,d',
        ],
        'administrator' => [
            'users' => 'c,r,u,d',
            'profile' => 'c,r,u,d',
            'inventory' => 'c,r,u,d',
            'discounts' => 'c,r,u,d',
            'accounts' => 'c,r,u,d',
            'reporting' => 'c,r,u,d',
        ]
    ],
    'permission_structure' => [
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
