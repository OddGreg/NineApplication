<?php

return [
    //
    // -----------------------------------------------------------------
    // = Application Title and Version
    // -----------------------------------------------------------------
    //
    'title'                  => 'Nine Application',
    'version'                => '0.5.0',

    //
    // -----------------------------------------------------------------
    // = Environmental settings
    // -----------------------------------------------------------------
    //
    'timezone'               => 'America/Vancouver',
    'locale'                 => 'en',
    'encoding'               => 'UTF-8',
    'base_path'              => dirname(__DIR__) . DIRECTORY_SEPARATOR,
    'asset_path'             => 'tests/public/assets',

    //
    // -----------------------------------------------------------------
    // = Fixed parameters for URL generation (may be overridden)
    // -----------------------------------------------------------------
    //
    'scheme'                 => 'http',
    'host'                   => 'localhost',
    'port'                   => '8080',

    //
    // -----------------------------------------------------------------
    // = Location of the primary route definitions file
    // -----------------------------------------------------------------
    //
    'routes'                 => 'tests/app/routes.php',

    //
    // -----------------------------------------------------------------
    // = Dependency Injection Loader
    // -----------------------------------------------------------------
    //
    'loader'                 => [
        // the dependency group to register
        'context'   => 'app',
        // the path to the folder containing dependency groups (1 file each)
        // note: the filename of the file determines the group name.
        // ie: <path>/app.php defines the group as 'app'
        'base_path' => BOOT . 'loaders/',
    ],

    //
    // -----------------------------------------------------------------
    // = Namespaced Controllers
    //
    //  Controllers are automatically injected as services by parsing
    //  the controllers located in the folder indicated by the
    //  CONTROLLERS constant.
    //
    //  However, you may need to add additional controllers that exist
    //  outside of the main controller namespace (as determined by the
    //  BaseController namespace).
    //
    //  Note: Automatically injected controllers determine their alias
    //        based on the name of the class. ie: DemoHelloController
    //        results in a service name of: demo.hello.controller. It
    //        is important not to use a service controller alias
    //        that conflicts with an already existing alias.
    // -----------------------------------------------------------------
    //
    'namespaced_controllers' =>
        [
        ],

    //
    // -----------------------------------------------------------------
    // = Http Middleware
    // -----------------------------------------------------------------
    //
    'middleware'             => [],

    //
    // -----------------------------------------------------------------
    // = Framework and Application Service Providers
    // -----------------------------------------------------------------
    //
    'providers'              => [],
];
