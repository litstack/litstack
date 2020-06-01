<?php

namespace FjordTest;

use FjordTest\Traits\TestHelpers;
use FjordTest\Traits\RefreshLaravel;
use FjordTest\Traits\InteractsWithConfig;
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

    public static function setUpBeforeClass(): void
    {
        self::setUpBeforeClassTraits();
    }

    public static function tearDownAfterClass(): void
    {
        self::tearDownAfterClassTraits();
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        parent::setUpTraits();

        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[RefreshLaravel::class])) {
            $this->fixMigrations();
        }
        if (isset($uses[InteractsWithConfig::class])) {
            $this->overrideConfigLoaderSingleton();
        }
    }



    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function tearDownTraits()
    {
        //
    }

    protected static function setUpBeforeClassTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[RefreshLaravel::class])) {
            static::createLaravelBackup();
        }
    }

    protected static function tearDownAfterClassTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[RefreshLaravel::class])) {
            static::refreshLaravel();
        }
    }

    /**
     * Resetting browser environment.
     */
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
