<?php

namespace Fjord\Application;

use Fjord\Application\Composer\HttpErrorComposer;
use Fjord\Application\Controllers\NotFoundController;
use Fjord\Application\Kernel\HandleRouteMiddleware;
use Fjord\Application\Kernel\HandleViewComposer;
use Fjord\Commands\FjordFormPermissions;
use Fjord\Support\Facades\Config;
use Fjord\Support\Facades\FjordRoute;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\View as ViewClass;

class ApplicationServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //dd(Application::class);
        //$this->app->alias('abc', 'fjord.app');

        $this->registerFormPermissionsCommand();

        // Fix: config_type
        $this->fixMigrations();
    }

    /**
     * Fix: config_type.
     */
    public function fixMigrations()
    {
        if (app()->runningInConsole()) {
            return;
        }
        // Fix: config_type
        try {
            if (! \Illuminate\Support\Facades\Schema::hasColumn('form_blocks', 'form_type')) {
                \Illuminate\Support\Facades\Schema::table('form_blocks', fn ($table) => $table->string('form_type')->after('model_id'));
            }
            if (! \Illuminate\Support\Facades\Schema::hasColumn('form_blocks', 'config_type')) {
                \Illuminate\Support\Facades\Schema::table('form_blocks', fn ($table) => $table->string('config_type')->after('model_id'));
            }
            if (! \Illuminate\Support\Facades\Schema::hasColumn('form_fields', 'form_type')) {
                \Illuminate\Support\Facades\Schema::table('form_fields', fn ($table) => $table->string('form_type')->after('id'));
            }
            if (! \Illuminate\Support\Facades\Schema::hasColumn('form_fields', 'config_type')) {
                \Illuminate\Support\Facades\Schema::table('form_fields', fn ($table) => $table->string('config_type')->after('id'));
            }
            $this->fixFormFields();
        } catch (\Throwable $e) {
        }
    }

    /**
     * Fix: config_type.
     */
    public function fixFormFields()
    {
        $fields = DB::table('form_fields')->where('config_type', '')->get();
        foreach ($fields as $field) {
            $key = "form.{$field->collection}.{$field->form_name}";
            $name = '';
            foreach (explode('.', $key) as $part) {
                $name .= '\\'.ucfirst(Str::camel($part));
            }

            $type = "FjordApp\Config".$name.'Config';
            DB::table('form_fields')->where('id', $field->id)->update([
                'config_type' => $type,
                'form_type'   => 'show',
            ]);
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router): void
    {
        $this->handleKernel($router);
        $this->fjordErrorPages();
        $this->addCssFilesFromConfig();
    }

    /**
     * Add css files from config fjord.assets.css.
     *
     * @return void
     */
    public function addCssFilesFromConfig()
    {
        $files = config('fjord.assets.css') ?? [];
        foreach ($files as $file) {
            $this->app['fjord.app']->addCssFile($file);
        }
    }

    /**
     * Handle kernel methods "handleRoute" and "handleView".
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function handleKernel(Router $router)
    {
        // Kernel method handleRoute gets executed here.
        $router->pushMiddlewareToGroup('web', HandleRouteMiddleware::class);

        // Kernel method handleView gets executed here.
        View::composer('fjord::app', HandleViewComposer::class);
    }

    /**
     * Better Fjord error pages.
     *
     * @return void
     */
    public function fjordErrorPages()
    {
        // Register route {any} after all service providers have been booted to
        // not override other routes.
        $this->app->booted(function () {
            FjordRoute::get('{any}', NotFoundController::class)
                ->where('any', '.*')
                ->name('not_found');
        });

        // Set view composer for error pages to use fjord error pages when needed.
        View::composer('errors::*', HttpErrorComposer::class);

        // This macro is needed to override the error page view.
        ViewClass::macro('setView', function (string $view) {
            $this->view = $view;
            $this->setPath(view('fjord::app')->getPath());

            return $this;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerFormPermissionsCommand()
    {
        // Bind singleton.
        $this->app->singleton('fjord.command.form-permissions', function ($app) {
            // Passing migrator to command.
            return new FjordFormPermissions($app['migrator']);
        });
        // Registering command.
        $this->commands(['fjord.command.form-permissions']);
    }
}
