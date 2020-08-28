<?php

namespace Tests\Foundation;

use Illuminate\Foundation\Console\CastMakeCommand;
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

    /** @test */
    public function test_job_command_is_registered()
    {
        $this->assertCommandExists('lit:job');
    }

    /** @test */
    public function test_component_command_is_registered()
    {
        $this->assertCommandExists('lit:component');
    }

    /** @test */
    public function test_request_command_is_registered()
    {
        $this->assertCommandExists('lit:request');
    }

    /** @test */
    public function test_provider_command_is_registered()
    {
        $this->assertCommandExists('lit:provider');
    }

    /** @test */
    public function test_resource_command_is_registered()
    {
        $this->assertCommandExists('lit:resource');
    }

    /** @test */
    public function test_cast_command_is_registered()
    {
        if (! class_exists(CastMakeCommand::class)) {
            $this->markTestSkipped('Cast command not available in Laravel '.Application::VERSION);
        }

        $this->assertCommandExists('lit:cast');
    }

    public function assertCommandExists($command)
    {
        $this->assertTrue(
            array_key_exists($command, Artisan::all()),
            "Failed asserting that artisan command {$command} exists."
        );
    }
}
