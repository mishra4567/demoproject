<?php

return [
    'BackEndUrl'=>env('SITE_URL'),
    'Admin' => [
        'routes' => [
            [
                'method' => 'POST',
                'url'    => '/admin/login',
                'role'   => 0,
                'label'  => 'Admin Login',
            ],
            [
                'method' => 'GET',
                'url'    => '/admin/dashboard',
                'role'   => 0,
                'label'  => 'Dashboard',
            ],
        ],
    ],

    'SiteApi' => [
        'routes' => [
            [
                'method' => 'POST',
                'url'    => '/api/login',
                'role'   => 0,
                'label'  => 'User Login',
            ],
            [
                'method' => 'GET',
                'url'    => '/api/products',
                'role'   => 0,
                'label'  => 'Product List',
            ],
        ],
    ],

    'VendorApi' => [
        'routes' => [
            [
                'method' => 'POST',
                'url'    => '/vendor/login',
                'role'   => 0,
                'label'  => 'Vendor Login',
            ],
            [
                'method' => 'GET',
                'url'    => '/vendor/products',
                'role'   => 0,
                'label'  => 'Vendor Products',
            ],
        ],
    ],

];
