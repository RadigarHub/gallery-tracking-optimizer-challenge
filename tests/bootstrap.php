<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

// Drop test database
passthru(
    sprintf(
        'APP_ENV=%s php "%s/../bin/console" doctrine:database:drop --if-exists --force',
        $_ENV['APP_ENV'],
        __DIR__
    )
);

// Create test database
passthru(
    sprintf(
        'APP_ENV=%s php "%s/../bin/console" doctrine:database:create --if-not-exists',
        $_ENV['APP_ENV'],
        __DIR__
    )
);

// Create test database schema
passthru(
    sprintf(
        'APP_ENV=%s php "%s/../bin/console" doctrine:schema:create',
        $_ENV['APP_ENV'],
        __DIR__
    )
);
