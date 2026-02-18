<?php

return [
    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    */
    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | Use a dedicated compiled directory to avoid Windows file locking issues
    | inside the default `storage/framework/views` folder.
    |
    */
    'compiled' => storage_path('framework/views-compiled'),
];

