<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => public_path('public'),
            'url' => env('APP_URL').'/public',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        'customers_address' => [
            'driver' => 'local',
            'root' => storage_path('app/public/img/customers/address'),
            'url' => env('APP_URL').'/storage/img/customers/address',
            'visibility' => 'public',
        ],

        'customers_dni' => [
            'driver' => 'local',
            'root' => storage_path('app/public/img/customers/dni'),
            'url' => env('APP_URL').'/storage/img/customers/dni',
            'visibility' => 'public',
        ],

        'customers_receipt' => [
            'driver' => 'local',
            'root' => storage_path('app/public/img/customers/receipt'),
            'url' => env('APP_URL').'/storage/img/customers/receipt',
            'visibility' => 'public',
        ],

        'cards' => [
            'driver' => 'local',
            'root' => storage_path('app/public/img/customers/cards'),
            'url' => env('APP_URL') . '/storage/img/customers/cards',
            'visibility' => 'public'
        ],

        'products' => [
            'driver' => 'local',
            'root' => storage_path('app/public/img/products'),
            'url' => env('APP_URL').'/storage/img/products',
            'visibility' => 'public',
        ],

        'spendings' => [
            'driver' => 'local',
            'root' => storage_path('app/public/img/spendings'),
            'url' => env('APP_URL').'/storage/img/spendings',
            'visibility' => 'public',
        ],

        'configs' => [
            'driver' => 'local',
            'root' => storage_path('app/public/img/configs'),
            'url' => env('APP_URL').'/storage/img/configs',
            'visibility' => 'public',
        ],
    ],

];
