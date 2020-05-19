<?php

namespace Fjord\Test;

use Fjord\FjordServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class SimpleFjordTestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        // The given order is important since fjord migrations require certain 
        // depency migrations to be executed before. 
        return [
            FjordServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
