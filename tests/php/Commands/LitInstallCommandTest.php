<?php

namespace Tests\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Lit\Models\User;
use Spatie\Permission\Models\Role;
use Tests\BackendTestCase;
use Tests\Traits\RefreshLaravel;

class LitInstallCommandTest extends BackendTestCase
{
    use RefreshLaravel;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lit:install --migrations=false');
        $this->fixMigrations();
    }

    /** @test */
    public function it_publishes_vendor_configs()
    {
        $this->assertFileExists(config_path('lit.php'));
        $this->assertFileExists(config_path('media-library.php'));
        $this->assertFileExists(config_path('translatable.php'));
        $this->assertFileExists(config_path('sluggable.php'));
    }

    /** @test */
    public function it_modifies_default_locales()
    {
        $translatable = require config_path('translatable.php');

        // dd(File::get(config_path('translatable.php')));
        $this->assertCount(2, $translatable['locales']);
        $this->assertTrue(in_array('de', $translatable['locales']));
        $this->assertTrue(in_array('en', $translatable['locales']));
    }

    /** @test */
    public function it_changes_media_model()
    {
        $mediaConfig = require config_path('media-library.php');

        $this->assertEquals(\Ignite\Crud\Models\Media::class, $mediaConfig['media_model']);
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
        $this->assertTrue(DB::table('lit_users')->where([
            'email' => 'admin@admin.com',
        ])->exists());
    }

    /** @test */
    public function it_assigns_admin_role_to_default_lit_user()
    {
        $user = DB::table('lit_users')->where([
            'email' => 'admin@admin.com',
        ])->first();

        $role = Role::where('name', 'admin')->where('guard_name', 'lit')->first();

        $this->assertTrue(
            DB::table('model_has_roles')
                ->where('model_type', 'Lit\\Models\\User')
                ->where('model_id', $user->id)
                ->where('role_id', $role->id)
                ->exists()
        );
    }

    /** @test */
    public function it_doesnt_create_default_admin_in_production()
    {
        $this->app['config']->set('app.env', 'production');
        DB::table('lit_users')->where('id', '!=', -1)->delete();
        $this->artisan('lit:install --migrations=false');
        $this->assertFalse(DB::table('lit_users')->where([
            'email' => 'admin@admin.com',
        ])->exists());
    }

    /** @test */
    public function it_creates_default_roles_and_permissions()
    {
        $permissions = [
            // Lit users.
            'create lit-users',
            'read lit-users',
            'update lit-users',
            'delete lit-users',
            // Lit user roles.
            'create lit-user-roles',
            'read lit-user-roles',
            'update lit-user-roles',
            'delete lit-user-roles',
            // Lit user role permissions.
            'read lit-role-permissions',
            'update lit-role-permissions',
        ];
        foreach ($permissions as $permission) {
            $this->assertDatabaseHas('permissions', ['name' => $permission]);
        }

        $this->assertDatabaseHas('roles', ['guard_name' => 'lit', 'name' => 'admin']);
        $this->assertDatabaseHas('roles', ['guard_name' => 'lit', 'name' => 'user']);
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
