<?php

namespace Lit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LitExtend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:extend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will install node packages lit and lit-permissions and extend your webpack.mix.js to extend your Lit Application.';

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
        $cmd = "cd {$base}; npm i vendor/litstack/litstack";
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
        $extension = File::get(lit_path('publish/extend/webpack.mix.extension.js'));
        $webpack = File::get(base_path('webpack.mix.js'));
        if (Str::contains($webpack, $extension)) {
            return;
        }

        $this->line('build webpack.mix');

        File::prepend(base_path('webpack.mix.js'), "\n".$extension);
    }
}
