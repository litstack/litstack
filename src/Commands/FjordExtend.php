<?php

namespace Fjord\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FjordExtend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:extend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This wizard will generate all the files needed for a new crud module';

    public function handle()
    {
        $this->runNpmInstall();
        $this->publishJs();
        $this->extendWebpack();
        $this->makeExampleRoute();
        $this->makeExampleController();
    }

    protected function makeController()
    {
        File::copy(
            fjord_path('publish/extend/controller/ExampleController.php'),
            app_path('Http/Controllers/Fjord/ExampleController.php')
        );
    }

    protected function makeRoute()
    {
        File::append(
            base_path('routes/fjord.php'),
            "\nRoute::get('/example', \App\Http\Controllers\Fjord\ExampleController::class)->name('example');"
        );
    }

    protected function runNpmInstall()
    {
        $base = base_path();
        shell_exec("cd {$base}; npm i vendor/aw-studio/fjord vendor/aw-studio/fjord-permissions");
    }

    protected function publishJs()
    {
        if (File::exists(resource_path('js/fjord'))) {
            return;
        }

        $this->line('publish fjord js app');

        File::copyDirectory(fjord_path('publish/extend/js/fjord'), resource_path('js/fjord'));
    }

    protected function extendWebpack()
    {
        $extension = File::get(fjord_path('publish/extend/webpack.mix.extension.js'));
        $webpack = File::get(base_path('webpack.mix.js'));
        if (Str::contains($webpack, $extension)) {
            return;
        }

        $this->line("build webpack.mix");

        File::append(base_path('webpack.mix.js'), "\n" . $extension);
    }
}
