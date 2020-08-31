<?php

namespace Ignite\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ExtendCommand extends Command
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
    protected $description = 'This will install the node package litstack and extend your webpack.mix.js to extend your Litstack Application.';

    /**
     * Create new ExtendCommand instance.
     *
     * @param  Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

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
        $extension = $this->files->get(
            lit_vendor_path('publish/extend/webpack.mix.extension.js')
        );

        $webpack = $this->files->get(base_path('webpack.mix.js'));

        if (Str::contains($webpack, $extension)) {
            return;
        }

        $this->line('build webpack.mix');

        $this->files->prepend(base_path('webpack.mix.js'), "\n".$extension);
    }
}
