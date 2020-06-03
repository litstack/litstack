<?php

namespace FjordTest;

use FjordTest\Traits\TestHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Dusk\TestCase as OrchestraDuskTestCase;

class BackendTestCase extends OrchestraDuskTestCase
{
    use RefreshDatabase, FjordTestCase, TestHelpers;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // ...
        $this->setUpTraits();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->tearDownTraits();
    }

    protected function setUpTheBrowserEnvironment()
    {
    }
    protected function registerShutdownFunction()
    {
    }
    public static function prepare()
    {
    }
}
