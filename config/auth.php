<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'pelanggans',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */
    'guards' => [

        'web' => [
            'driver'   => 'session',
            'provider' => 'pelanggans',   // atau provider lain sesuai kebutuhan
        ],

        'pelanggan' => [
            'driver'   => 'session',
            'provider' => 'pelanggans',
        ],

        'admin' => [
            'driver'   => 'session',
            'provider' => 'admins',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'pelanggans' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Pelanggan::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Admin::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'pelanggans' => [
            'provider' => 'pelanggans',
            'table'    => 'password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ],

        'admins' => [
            'provider' => 'admins',
            'table'    => 'password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */
    'password_timeout' => 10800,

];
