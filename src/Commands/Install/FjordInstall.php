<?php

namespace AwStudio\Fjord\Commands\Install;

use AwStudio\Fjord\Commands\Traits\RolesAndPermissions;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class FjordInstall extends Command
{
    use RolesAndPermissions,
        InstallVendorConfigs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This wizard will take you through the installation process';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $filesystem)
    {
        // http://patorjk.com/software/taag/#p=display&h=1&v=0&f=Slant&t=Fjord%20Install
        $this->info("    ______ _                   __   ____              __          __ __");
        $this->info("   / ____/(_)____   _____ ____/ /  /  _/____   _____ / /_ ____ _ / // /");
        $this->info("  / /_   / // __ \ / ___// __  /   / / / __ \ / ___// __// __ `// // / ");
        $this->info(" / __/  / // /_/ // /   / /_/ /  _/ / / / / /(__  )/ /_ / /_/ // // /  ");
        $this->info("/_/  __/ / \____//_/    \__,_/  /___//_/ /_//____/ \__/ \__,_//_//_/   ");
        $this->info("    /___/                                                              ");

        $this->info("\n----- start -----\n");

        $this->vendorConfigs();
        $this->call('fjord:guard');

        $this->handleFjordPublishable();
        $this->publishFjordResources();

        $this->createDefaultRoles();
        $this->createDefaultPermissions();

        $this->publishDashboardController();
        $this->publishFjordServiceProvider();
        $this->publishFjordKernel();
        $this->makeModelDirs();

        $this->info("\n----- finished -----\n");

        $this->info('installation complete - run php artisan fjord:admin to create an admin user');
    }

    /**
     * Make directory if not exists.
     *
     * @param string $path
     * @return void
     */
    private function makeDirectory(string $path)
    {
        if (File::exists($path)) {
            return;
        }

        File::makeDirectory($path);
    }

    protected function publishFjordKernel()
    {
        if (File::exists(app_path('Fjord/Kernel.php'))) {
            return;
        }

        $this->line("publishing fjord application");

        $this->makeDirectory(app_path('Fjord'));
        File::copy(
            fjord_path('publish/fjord/Kernel.php'),
            app_path('Fjord/Kernel.php')
        );
    }


    /**
     * Publish Fjord config and assets
     *
     * @return void
     */
    private function handleFjordPublishable()
    {
        $this->info('publishing fjord config & migrations');

        $this->callSilent('vendor:publish', [
            '--provider' => "AwStudio\Fjord\FjordServiceProvider"
        ]);

        // migrate tables
        if (\App::environment(['local', 'staging'])) {
            $this->callSilent('migrate');
        } else {
            $this->call('migrate');
        }
    }

    /**
     * Publish Fjord resources.
     *
     * @return void
     */
    public function publishFjordResources()
    {
        if (is_dir(fjord_resource_path()) && fjord_resource_path() !== resource_path()) {
            return;
        }

        $this->info('publishing fjord resources');
        // clear the config cache, otherwise, fjord_resource_path() will return
        // the resource path itself, which is present for shure
        $this->callSilent('config:clear');

        File::copyDirectory(fjord_path('publish/resources/fjord'), resource_path('fjord'));
    }

    /**
     * Publish Fjord DashboardController.
     *
     * @return void
     */
    public function publishDashboardController()
    {
        $this->makeDirectory(app_path('Http/Controllers/Fjord'));

        if (File::exists(app_path('Http/Controllers/Fjord/DashboardController.php'))) {
            return;
        }

        $this->info('publishing DashboardController');

        File::copy(
            fjord_path('publish/controllers/DashboardController.php'),
            app_path('Http/Controllers/Fjord/DashboardController.php')
        );
    }

    public function publishFjordServiceProvider()
    {
        if (File::exists(app_path('Http/Providers/FjordServiceProvider.php'))) {
            return;
        }

        $this->info('publishing FjordServiceProvider');

        File::copy(
            fjord_path('publish/providers/FjordServiceProvider.php'),
            app_path('Providers/FjordServiceProvider.php')
        );
    }

    /**
     * Create Model 
     *
     * @return void
     */
    private function makeModelDirs()
    {
        $this->makeDirectory(app_path('Models'));
        $this->makeDirectory(app_path('Models/Translations'));
    }
}
