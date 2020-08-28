<?php

namespace Tests\Foundation;

use Illuminate\Support\Facades\Artisan;
use Tests\BackendTestCase;

class CommandsRegisteredTest extends BackendTestCase
{
    /** @test */
    public function test_livewire_command_is_registered()
    {
        $this->assertCommandExists('lit:livewire');
    }

    /** @test */
    public function test_install_command_is_registered()
    {
        $this->assertCommandExists('lit:install');
    }

    /** @test */
    public function test_admin_command_is_registered()
    {
        $this->assertCommandExists('lit:admin');
    }

    /** @test */
    public function test_user_command_is_registered()
    {
        $this->assertCommandExists('lit:user');
    }

    /** @test */
    public function test_form_permissions_command_is_registered()
    {
        $this->assertCommandExists('lit:form-permissions');
    }

    /** @test */
    public function test_nav_command_is_registered()
    {
        $this->assertCommandExists('lit:nav');
    }

    public function assertCommandExists($command)
    {
        $this->assertTrue(
            array_key_exists($command, Artisan::all()),
            "Failed asserting that artisan command {$command} exists."
        );
    }
}
