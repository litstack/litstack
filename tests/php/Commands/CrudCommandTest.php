<?php

namespace Tests\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\BackendTestCase;

class CrudCommandTest extends BackendTestCase
{
    /** @test */
    public function it_generates_files()
    {
        $this->artisan('lit:crud Foo');

        // Controller
        $this->assertFileExists(base_path('lit/app/Http/Controllers/Crud/FooController.php'));
        $this->assertInstanceOf(\Ignite\Crud\Controllers\CrudController::class, new \Lit\Http\Controllers\Crud\FooController());
        // Config
        $this->assertFileExists(base_path('lit/app/Config/Crud/FooConfig.php'));
        $this->assertInstanceOf(\Ignite\Crud\Config\CrudConfig::class, new \Lit\Config\Crud\FooConfig());
        // Migration
        $this->assertCount(1, File::glob(database_path('migrations/*create_foos_table.php')));
        // Model
        $this->assertFileExists(app_path('Models/Foo.php'));

        $this->artisan('migrate');
        $this->assertTableExists('foos');
    }

    /** @test */
    public function it_generates_files_with_given_config_name()
    {
        $this->artisan('lit:crud Foo FooBarConfig');

        $this->assertFileExists(base_path('lit/app/Http/Controllers/Crud/FooBarController.php'));
        $this->assertInstanceOf(\Ignite\Crud\Controllers\CrudController::class, new \Lit\Http\Controllers\Crud\FooBarController());
        $this->assertFileExists(base_path('lit/app/Config/Crud/FooBarConfig.php'));
        $this->assertInstanceOf(\Ignite\Crud\Config\CrudConfig::class, new \Lit\Config\Crud\FooBarConfig());
    }

    /** @test */
    public function it_generates_two_tables_in_migration_for_translatable_cruds()
    {
        $this->artisan('lit:crud Bar -t');

        $this->assertCount(1, File::glob(database_path('migrations/*create_bars_tables.php')));
        $this->assertFileExists(base_path('app/Models/Translations/BarTranslation.php'));

        $this->artisan('migrate');
        $this->assertTableExists('bars');
        $this->assertTableExists('bar_translations');

        $this->assertTableHasColumn('bar_translations', 'id');
        $this->assertTableHasColumn('bar_translations', 'bar_id');
        $this->assertTableHasColumn('bar_translations', 'locale');
    }

    /** @test */
    public function test_migration_sluggable()
    {
        $this->artisan('lit:crud Baz -s');
        $this->artisan('migrate');
        $this->assertTableExists('bazs');
    }

    /** @test */
    public function test_generation_for_sluggable_and_translatable()
    {
        $this->artisan('lit:crud Dummy -s -t');

        $this->assertFileExists(base_path('app/Models/Translations/DummyTranslation.php'));
    }

    protected function assertTableExists($table)
    {
        $this->assertTrue(Schema::hasTable($table), "Failed asserting that table {$table} exists.");
    }

    protected function assertTableHasColumn($table, $column)
    {
        $this->assertTrue(Schema::hasColumn($table, $column), "Failed asserting that table {$table} hast column {$column}.");
    }
}
