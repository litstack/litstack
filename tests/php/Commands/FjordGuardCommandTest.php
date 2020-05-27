<?php

namespace FjordTest\Commands;

use FjordTest\BackendTestCase;
use Fjord\User\Models\FjordUser;
use FjordTest\Traits\RefreshLaravel;

class FjordGuardCommandTest extends BackendTestCase
{
    use RefreshLaravel;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('fjord:guard');
    }

    public function tearDown(): void
    {
        self::refreshLaravel();
    }

    /** @test */
    public function it_creates_fjord_guard()
    {
        $authConfig = require config_path('auth.php');

        $this->assertArrayHasKey('fjord', $authConfig['guards']);
    }

    /** @test */
    public function it_creates_fjord_users_provider()
    {
        $authConfig = require config_path('auth.php');

        $this->assertArrayHasKey('fjord_users', $authConfig['providers']);
    }
}
