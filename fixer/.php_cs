<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('tests/resources/*')
    ->in([
        __DIR__ . '/../src',
        __DIR__ . '/../tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = require '.php_cs_config';
$config->setFinder($finder);

return $config;