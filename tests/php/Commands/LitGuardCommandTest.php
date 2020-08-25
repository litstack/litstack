<?php

namespace Tests\Commands;

use Tests\BackendTestCase;
use Tests\Traits\RefreshLaravel;

class LitGuardCommandTest extends BackendTestCase
{
    use RefreshLaravel;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lit:guard');
    }

    public function tearDown(): void
    {
        self::refreshLaravel();
    }

    /** @test */
    public function it_creates_lit_guard()
    {
        $authConfig = require config_path('auth.php');

        $this->assertArrayHasKey('lit', $authConfig['guards']);
    }

    /** @test */
    public function it_creates_lit_users_provider()
    {
        $authConfig = require config_path('auth.php');

        $this->assertArrayHasKey('lit_users', $authConfig['providers']);
    }
}
