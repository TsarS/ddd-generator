<?php
declare(strict_types=1);

// ddd.php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/DDDGenerator.php';
require_once __DIR__ . '/src/Command/CreateAppCommand.php';

use YourVendor\DDDGenerator\Command\CreateAppCommand;
use Symfony\Component\Console\Application;

$app = new Application('DDD Generator', '1.0.0');
$app->add(new CreateAppCommand());
$app->run();
