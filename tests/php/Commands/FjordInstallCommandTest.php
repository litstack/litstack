<?php

namespace FjordTest\Commands;

use Fjord\User\Models\FjordUser;
use FjordTest\BackendTestCase;
use FjordTest\Traits\RefreshLaravel;

class FjordInstallCommandTest extends BackendTestCase
{
    use RefreshLaravel;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('fjord:install --migrations=false');
        $this->fixMigrations();
    }

    /** @test */
    public function it_publishes_vendor_configs()
    {
        $this->assertFileExists(config_path('fjord.php'));
        $this->assertFileExists(config_path('media-library.php'));
        $this->assertFileExists(config_path('translatable.php'));
        $this->assertFileExists(config_path('sluggable.php'));
    }

    /** @test */
    public function it_modifies_default_locales()
    {
        $translatable = require config_path('translatable.php');

        $this->assertCount(2, $translatable['locales']);
        $this->assertTrue(in_array('de', $translatable['locales']));
        $this->assertTrue(in_array('en', $translatable['locales']));
    }

    /** @test */
    public function it_changes_media_model()
    {
        $mediaConfig = require config_path('media-library.php');

        $this->assertEquals(\Fjord\Crud\Models\Media::class, $mediaConfig['media_model']);
    }

    /** @test */
    public function it_changes_create_permission_tables_migration_name()
    {
        // Using dusk testbench since migrations wont be published to avoid.
        $files = glob(parent::getBasePath().'/database/migrations/*_create_permission_tables.php');

        $this->assertCount(1, $files);
        $this->assertStringEndsWith('2020_00_00_000000_create_permission_tables.php', $files[0]);
    }

    /** @test */
    public function it_creates_default_admin()
    {
        $this->assertTrue(FjordUser::where([
            'email' => 'admin@admin.com',
        ])->exists());
    }

    /** @test */
    public function it_assigns_admin_role_to_default_fjord_user()
    {
        $user = FjordUser::where([
            'email' => 'admin@admin.com',
        ])->first();

        $this->assertTrue(
            $user->roles()
                ->where('name', 'admin')
                ->where('guard_name', 'fjord')
                ->exists()
        );
    }

    /** @test */
    public function it_doesnt_create_default_admin_in_production()
    {
        $this->app['config']->set('app.env', 'production');
        FjordUser::where('id', '!=', -1)->delete();
        $this->artisan('fjord:install --migrations=false');
        $this->assertFalse(FjordUser::where([
            'email' => 'admin@admin.com',
        ])->exists());
    }

    /** @test */
    public function it_creates_default_roles_and_permissions()
    {
        $permissions = [
            // Fjord users.
            'create fjord-users',
            'read fjord-users',
            'update fjord-users',
            'delete fjord-users',
            // Fjord user roles.
            'create fjord-user-roles',
            'read fjord-user-roles',
            'update fjord-user-roles',
            'delete fjord-user-roles',
            // Fjord user role permissions.
            'read fjord-role-permissions',
            'update fjord-role-permissions',
        ];
        foreach ($permissions as $permission) {
            $this->assertDatabaseHas('permissions', ['name' => $permission]);
        }

        $this->assertDatabaseHas('roles', ['guard_name' => 'fjord', 'name' => 'admin']);
        $this->assertDatabaseHas('roles', ['guard_name' => 'fjord', 'name' => 'user']);
    }
}
