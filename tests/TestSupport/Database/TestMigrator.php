<?php

namespace Fjord\Test\TestSupport\Database;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class TestMigrator
{
    protected $app;

    protected $publishes = [
        \Spatie\Permission\PermissionServiceProvider::class
    ];

    public function __construct($app)
    {
        $this->app = $app;

        $this->schema = $this->app['db']->connection()->getSchemaBuilder();
        $this->migrator = $this->app['migrator'];
    }

    public function createEmployees($schema)
    {
        return;
        Schema::create('crud_employees', function ($table) {
            $table->bigIncrements('id');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            //$table->foreign('department_id')->references('id')->on('departments');
            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    public function migrate()
    {
        foreach (get_class_methods(self::class) as $method) {
            if (!Str::startsWith($method, 'create')) {
                continue;
            }
            call_user_func([$this, $method], $this->schema);
        }
    }
}
