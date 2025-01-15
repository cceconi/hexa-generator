<?php

// set to run indefinitely if needed
set_time_limit(0);

// limit output
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// import the Symfony Console Application

use Apido\HexaGenerator\Command\GenerateRoleCommand;
use Apido\HexaGenerator\Command\GenerateUseCaseCommand;
use Symfony\Component\Console\Application;

function includeAutoload()
{
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        include_once __DIR__ . '/../vendor/autoload.php';
        return;
    }
    if (file_exists(__DIR__ . '/../../../autoload.php')) {
        include_once __DIR__ . '/../../../autoload.php';
        return;
    }
    throw new RuntimeException("Cannot find autoload.php file.");
}

// include the composer autoloader
includeAutoload();

$app = new Application('Hexa-generator command line', "1.0.0");
$app->setAutoExit(false);

$commands = [
    new GenerateUseCaseCommand(),
    new GenerateRoleCommand()
];

$app->addCommands($commands);
$app->run();
