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
    protected $description = 'This will install npm packages fjord and fjord-permissions and extend your webpack.mix.js to extend your Fjord Application.';

    public function handle()
    {
        $this->runNpmInstall();
        $this->extendWebpack();
    }

    protected function runNpmInstall()
    {
        $this->line("build npm packages");
        $base = base_path();
        shell_exec("cd {$base}; npm i vendor/aw-studio/fjord vendor/aw-studio/fjord-permissions");
    }

    protected function extendWebpack()
    {
        $extension = File::get(fjord_path('publish/extend/webpack.mix.extension.js'));
        $webpack = File::get(base_path('webpack.mix.js'));
        if (Str::contains($webpack, $extension)) {
            return;
        }

        $this->line("build webpack.mix");

        File::prepend(base_path('webpack.mix.js'), "\n" . $extension);
    }
}
