<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
passthru('php bin/console doctrine:database:drop --env=test --force --if-exists');
passthru('php bin/console doctrine:database:create --env=test');

// run migrations
passthru('php bin/console doctrine:migrations:migrate --env=test -n');
passthru('php bin/console doctrine:fixtures:load --env=test -n');