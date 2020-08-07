<?php

namespace Fjord\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
    protected $description = 'This will install node packages fjord and fjord-permissions and extend your webpack.mix.js to extend your Fjord Application.';

    /**
     * Handle command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $this->line('build npm packages');
        $this->runNpmInstall(base_path());
        $this->extendWebpack();
    }

    /**
     * Run npm install.
     *
     * @param  string $base
     * @param  bool   $verbose
     * @return void
     */
    protected function runNpmInstall($base, $verbose = false)
    {
        $cmd = "cd {$base}; npm i vendor/aw-studio/fjord";
        if ($verbose) {
            passthru($cmd);
        } else {
            shell_exec($cmd);
        }
    }

    /**
     * Extend webpack file.
     *
     * @return void
     */
    protected function extendWebpack()
    {
        $extension = File::get(fjord_path('publish/extend/webpack.mix.extension.js'));
        $webpack = File::get(base_path('webpack.mix.js'));
        if (Str::contains($webpack, $extension)) {
            return;
        }

        $this->line('build webpack.mix');

        File::prepend(base_path('webpack.mix.js'), "\n".$extension);
    }
}
