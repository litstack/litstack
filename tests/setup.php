<?php

require __DIR__ . '/../vendor/autoload.php';

// Installing Fjord and starting test after to load namespaces of created FjordApp.

use FjordTest\BackendTestCase;


$test = new BackendTestCase;
$test->setUp();

$test->installFjord(
    $force = true
);
echo "\033[32m Installed Fjord successfully.\n\033[0m";

/*
echo "\033[31m Failed to install fjord.\n\033[0m";
try {

    echo "\033[32m Installed Fjord successfully.\n\033[0m";
} catch (Throwable $e) {
    
}
*/
