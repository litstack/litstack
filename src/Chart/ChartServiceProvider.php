<?php

namespace Ignite\Chart;

use Carbon\CarbonInterface;
use Ignite\Chart\Console\ChartCommand;
use Ignite\Chart\Engine\ApexAreaEngine;
use Ignite\Chart\Engine\ApexBarEngine;
use Ignite\Chart\Engine\ApexDonutEngine;
use Ignite\Chart\Engine\ApexEngine;
use Ignite\Chart\Engine\ApexProgressEngine;
use Ignite\Chart\Engine\ChartEngineResolver;
use Ignite\Chart\Engine\NumberEngine;
use Ignite\Chart\Loader\AreaLoader;
use Ignite\Chart\Loader\BarLoader;
use Ignite\Chart\Loader\ChartLoaderResolver;
use Ignite\Chart\Loader\DonutLoader;
use Ignite\Chart\Loader\NumberLoader;
use Ignite\Chart\Loader\ProgressLoader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ChartServiceProvider extends LaravelServiceProvider
{
    /**
     * The Commands that should be registered.
     *
     * @var array
     */
    protected $commands = [
        'Chart' => 'lit.command.chart',
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->macros();

        $this->registerCommands();

        $this->registerChartEngineResolver();
        $this->registerChartLoaderResolver();

        $this->registerChartFactory();
    }

    /**
     * Register commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        foreach ($this->commands as $command => $abstract) {
            call_user_func_array([$this, "register{$command}Command"], [$abstract]);
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerChartCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ChartCommand($app['files'], $app['lit']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerAdminCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ChartCommand($app['files'], $app['lit']);
        });
    }

    /**
     * Register chart factory.
     *
     * @return void
     */
    public function registerChartFactory()
    {
        $this->app->singleton('lit.chart.factory', function () {
            return new ChartFactory($this->app['lit.chart.engine.resolver']);
        });
    }

    /**
     * Register EngineResolver.
     *
     * @return void
     */
    public function registerChartLoaderResolver()
    {
        $this->app->singleton('lit.chart.loader.resolver', function () {
            $resolver = new ChartLoaderResolver();

            $resolver->register('area', function ($config, $engine) {
                return new AreaLoader($config, $engine);
            });
            $resolver->register('bar', function ($config, $engine) {
                return new BarLoader($config, $engine);
            });
            $resolver->register('donut', function ($config, $engine) {
                return new DonutLoader($config, $engine);
            });
            $resolver->register('progress', function ($config, $engine) {
                return new ProgressLoader($config, $engine);
            });
            $resolver->register('number', function ($config, $engine) {
                return new NumberLoader($config, $engine);
            });

            return $resolver;
        });
    }

    /**
     * Register EngineResolver.
     *
     * @return void
     */
    public function registerChartEngineResolver()
    {
        $this->app->singleton('lit.chart.engine.resolver', function () {
            $resolver = new ChartEngineResolver();

            $this->registerApexEngine($resolver);

            return $resolver;
        });
    }

    /**
     * Register ApexEngine.
     *
     * @param ChartEngineResolver $resolver
     *
     * @return void
     */
    public function registerApexEngine(ChartEngineResolver $resolver)
    {
        $resolver->register('apex.area', function () {
            return new ApexAreaEngine();
        });
        $resolver->register('apex.bar', function () {
            return new ApexBarEngine();
        });
        $resolver->register('apex.donut', function () {
            return new ApexDonutEngine();
        });
        $resolver->register('apex.progress', function () {
            return new ApexProgressEngine();
        });
        $resolver->register('number', function () {
            return new NumberEngine();
        });
    }

    /**
     * Register chart macros.
     *
     * @return void
     */
    public function macros()
    {
        Builder::macro(
            'whereInSecond',
            fn ($column, CarbonInterface $time) => $this
                ->whereBetween($column, [
                    $time->copy()->startOfSecond(),
                    $time->copy()->endOfSecond(),
                ])
        );

        Builder::macro(
            'whereInMinute',
            fn ($column, CarbonInterface $time) => $this
                ->whereBetween($column, [
                    $time->copy()->startOfMinute(),
                    $time->copy()->endOfMinute(),
                ])
        );

        Builder::macro(
            'whereInHour',
            fn ($column, CarbonInterface $time) => $this
                ->whereBetween($column, [
                    $time->copy()->startOfHour(),
                    $time->copy()->endOfHour(),
                ])
        );

        Builder::macro(
            'whereInHours',
            fn ($column, CarbonInterface $time, int $count) => $this
                ->whereBetween($column, [
                    $time->copy()->startOfHour(),
                    $time->copy()->addHours($count)->endOfHour(),
                ])
        );

        Builder::macro(
            'whereInDay',
            fn ($column, CarbonInterface $time) => $this
                ->whereBetween($column, [
                    $time->copy()->startOfDay(),
                    $time->copy()->endOfDay(),
                ])
        );

        Builder::macro(
            'whereInDays',
            fn ($column, CarbonInterface $time, int $count) => $this
                ->whereBetween($column, [
                    $time->copy()->startOfDay(),
                    $time->copy()->addDays($count)->endOfDay(),
                ])
        );

        Builder::macro(
            'whereInWeek',
            fn ($column, CarbonInterface $time) => $this
                ->whereBetween($column, [
                    $time->copy()->startOfWeek(),
                    $time->copy()->endOfWeek(),
                ])
        );

        Builder::macro(
            'whereInWeeks',
            fn ($column, CarbonInterface $time, int $count) => $this
                ->whereBetween($column, [
                    $time->copy()->startOfWeek(),
                    $time->copy()->addWeeks($count)->endOfWeek(),
                ])
        );

        Builder::macro(
            'whereInMonth',
            fn ($column, CarbonInterface $time) => $this
                ->whereBetween($column, [
                    $time->copy()->startOfMonth(),
                    $time->copy()->endOfMonth(),
                ])
        );

        Builder::macro(
            'whereInYear',
            fn ($column, CarbonInterface $time) => $this
                ->whereBetween($column, [
                    $time->copy()->startOfYear(),
                    $time->copy()->endOfYear(),
                ])
        );

        Builder::macro(
            'whereInDecade',
            fn ($column, CarbonInterface $time) => $this
                ->whereBetween($column, [
                    $time->copy()->startOfDecade(),
                    $time->copy()->startOfDecade(),
                ])
        );
    }
}
