<?php

namespace FjordTest;

use FjordTest\Traits\TestHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Dusk\TestCase as OrchestraDuskTestCase;

class BackendTestCase extends OrchestraDuskTestCase
{
    use RefreshDatabase;
    use FjordTestCase;
    use TestHelpers;

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

    /**
     * Begin a server for the tests.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::setUpBeforeClassTraits();
    }

    /**
     * Kill our server.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::tearDownAfterClassTraits();
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
