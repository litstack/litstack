<?php

namespace Ignite\Commands\Install;

use Ignite\Commands\Traits\RolesAndPermissions;
use Ignite\User\Models\User;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class LitInstall extends Command
{
    use RolesAndPermissions;
    use InstallVendorConfigs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:install 
                            {--migrations= : Whether to publish migrations or not }';

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
        // http://patorjk.com/software/taag/#p=display&h=1&v=0&f=Slant&t=Lit%20Install
        $this->info('    __     _  __     ____              __          __ __');
        $this->info('   / /    (_)/ /_   /  _/____   _____ / /_ ____ _ / // /');
        $this->info('  / /    / // __/   / / / __ \ / ___// __// __ `// // / ');
        $this->info(' / /___ / // /_   _/ / / / / /(__  )/ /_ / /_/ // // /  ');
        $this->info('/_____//_/ \__/  /___//_/ /_//____/ \__/ \__,_//_//_/   ');
        $this->info('                                                        ');

        $this->info("\n----- start -----\n");
        $this->vendorConfigs();
        $this->call('lit:guard');

        $this->handleLitPublishable();

        $this->createDefaultRoles();
        $this->createDefaultPermissions();

        $this->publishLit();
        $this->makeModelDirs();

        $this->defaultUser();

        $this->info("\n----- finished -----\n");

        $this->info('installation complete - run php artisan lit:admin to create an admin user');
    }

    public function defaultUser()
    {
        if (config('app.env') == 'production') {
            return;
        }
        if (User::where('username', 'admin')->orWhere('email', 'admin@admin')->exists()) {
            return;
        }
        // $user = User::firstOrCreate([
        //     'username' => 'admin',
        //     'email'    => 'admin@admin.com',
        // ], [
        //     'first_name' => 'admin',
        //     'last_name'  => '',
        //     'password'   => bcrypt('secret'),
        // ]);
        $user = new User([
            'username'   => 'admin',
            'email'      => 'admin@admin.com',
            'first_name' => 'admin',
            'last_name'  => '',
        ]);

        $user->password = bcrypt('secret');
        $user->save();

        $user->assignRole('admin');

        $this->info('created default admin (email: admin@admin.com, password: secret)');
    }

    /**
     * Make directory if not exists.
     *
     * @param string $path
     *
     * @return void
     */
    private function makeDirectory(string $path)
    {
        if (File::exists($path)) {
            return;
        }

        File::makeDirectory($path);
    }

    public function migrations()
    {
        $migrations = $this->option('migrations');

        return $migrations !== 'false' && $migrations !== false;
    }

    /**
     * Publish Lit config and assets.
     *
     * @return void
     */
    private function handleLitPublishable()
    {
        $this->info('publishing lit config & migrations');
        if ($this->migrations()) {
            $this->callSilent('vendor:publish', [
                '--provider' => "Ignite\LitServiceProvider",
                '--tag'      => 'migrations',
            ]);
        }

        $this->callSilent('vendor:publish', [
            '--provider' => "Ignite\LitServiceProvider",
            '--tag'      => 'config',
        ]);

        // Migrate tables.
        if (App::environment(['local', 'staging'])) {
            $this->callSilent('migrate');
        } else {
            $this->call('migrate');
        }
    }

    /**
     * Publish Lit resources.
     *
     * @return void
     */
    public function publishLit()
    {
        if (File::exists(base_path('lit/app/Kernel.php'))) {
            return;
        }
        $this->info('publishing lit');
        // clear the config cache, otherwise, lit_resource_path() will return
        // the resource path itself, which is present for shure
        $this->callSilent('config:clear');

        File::copyDirectory(realpath(lit_path('publish/lit')), base_path('lit'));

        $composer = json_decode(File::get(base_path('composer.json')), true);
        $composer['autoload']['psr-4']['Lit\\'] = 'lit/app/';
        File::put(base_path('composer.json'), json_encode(
            $composer,
            JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES
        ));
        shell_exec('composer dumpautoload');
    }

    /**
     * Create Model.
     *
     * @return void
     */
    private function makeModelDirs()
    {
        $this->makeDirectory(app_path('Models'));
        $this->makeDirectory(app_path('Models').DIRECTORY_SEPARATOR.'Translations');
    }
}
