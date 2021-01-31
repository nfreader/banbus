<?php

//Production safe defaults
//DO NOT EDIT THIS FILE!

$settings = [];

$settings['debug'] = false;

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['temp'] = $settings['root'] . '/tmp';
$settings['public'] = $settings['root'] . '/public';

$settings['app'] = [
  'name' => 'Banbus',
  'timezone' => 'UTC',
  'github' => 'nfreader/banbus'
];

$settings['modules'] = [
  'public_bans' => true,
  'personal_bans' => true
];

$settings['twig'] = [
  'paths' => [
    __DIR__ . '/../views',
  ],
  'options' => [
    'debug' => false,
    'cache_enabled' => true,
    'cache_path' => $settings['temp'] . '/twig',
  ],
];

// Error handler
$settings['error'] = [
  'display_error_details' => false,
  'log_errors' => true,
  'log_error_details' => true,
];

$settings['session'] = [
  'name' => 'app',
  'cache_expire' => 0,
];

$settings['db'] = [
    'method' => 'mysql',
    'host' => 'bb_mariadb',
    'port' => 3306,
    'encoding' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    // Enable identifier quoting
    'quoteIdentifiers' => true,
    // Set to null to use MySQL servers timezone
    'timezone' => null,
    // Disable meta data cache
    'cacheMetadata' => false,
    // Disable query logging
    'log' => false,
    // PDO options
    'flags' => [
        PDO::ATTR_PERSISTENT               => true,
        PDO::ATTR_ERRMODE                  => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE       => PDO::FETCH_OBJ,
        PDO::ATTR_STRINGIFY_FETCHES        => false,
        PDO::ATTR_EMULATE_PREPARES         => false
    ]
];

return $settings;
