<?php

use Nine\Application\Configurations\CoreConfigurator;

return [
    'di' => [
        // the identifier given to this configuration set.
        'name'        => 'app.di',
        // the path to the folder that contains configuration files
        // for this set.
        'config_path' => CONFIG,
        // the loader priority.
        'priority'    => 'high', # 'high' | 'normal' | 'low' | int
        // the list of Configurators in this set.
        'config'      => [
            // the configurator
            CoreConfigurator::class => [
                // the identifier for this Configurator
                'name'     => 'core.configuration',
                // the data set loaded by the ConfigFileReader class
                // defaults to [] if not supplied.
                'dataset'  => 'core',
                // the set priority. Defaults to 'normal' if not supplied.
                'priority' => 'high',
                // any settings to add or to override settings from the data set.
                // defaults to [] if not supplied.
                'config'   => ['cargo' => 'shamalam'],
            ],
        ],
    ],
];
