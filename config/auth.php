<?php
return [

    'defaults' => [
        'guard' => 'owner',
        'passwords' => 'owners',
    ],



    'guards' => [
        'owner' => [
            'driver' => 'session',
            'provider' => 'owners',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'kepala_dapur' => [
            'driver' => 'session',
            'provider' => 'kepala_dapurs',
        ],

        'distributor' => [
            'driver' => 'session',
            'provider' => 'distributors',
        ]
    ],



    'providers' => [        
        'owners' => [
            'driver' => 'eloquent',
            'model' => App\Models\Owner::class,
        ],

        
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'kepala_dapurs' => [
            'driver' => 'eloquent',
            'model' => App\Models\KepalaDapur::class,
        ],

        'distributors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Distributor::class,
        ]
    ],



    'passwords' => [
        'owners' => [
            'provider' => 'owners',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],



    'password_timeout' => 10800,

];
