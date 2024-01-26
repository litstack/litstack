<?php

namespace Ignite\Foundation\Console;

use Ignite\Foundation\LitstackServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class InstallCommand extends Command
{
    use Concerns\InstallsGuard,
        Concerns\InstallsVendorConfigs;

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
     * Filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Create new InstallCommand instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
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
        $this->installGuard();

        $this->handleLitPublishable();

        $this->createDefaultRoles();

        $this->publishLit();
        $this->makeModelDirs();

        $this->defaultUser();

        $this->info("\n----- finished -----\n");

        $this->info('installation complete - run php artisan lit:admin to create an admin user');
    }

    /**
     * Create default roles.
     *
     * @return void
     */
    protected function createDefaultRoles()
    {
        Role::firstOrCreate(['guard_name' => config('lit.guard'), 'name' => 'admin']);
        Role::firstOrCreate(['guard_name' => config('lit.guard'), 'name' => 'user']);
    }

    /**
     * Default user.
     *
     * @return void
     */
    protected function defaultUser()
    {
        if (config('app.env') == 'production') {
            return;
        }
        if (DB::table('lit_users')->where('username', 'admin')->orWhere('email', 'admin@admin')->exists()) {
            return;
        }

        DB::table('lit_users')->insert([
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'first_name' => 'admin',
            'last_name' => '',
            'password' => bcrypt('secret'),
        ]);
        DB::table(config('permission.table_names.model_has_roles'))->insert([
            'role_id' => Role::where('name', 'admin')->where('guard_name', 'lit')->first()->id,
            'model_type' => 'Lit\\Models\\User',
            'model_id' => DB::table('lit_users')->where('email', 'admin@admin.com')->first()->id,
        ]);

        $this->info('created default admin (email: admin@admin.com, password: secret)');
    }

    /**
     * Make directory if not exists.
     *
     * @return void
     */
    private function makeDirectory(string $path)
    {
        if ($this->files->exists($path)) {
            return;
        }

        $this->files->makeDirectory($path);
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
                '--provider' => LitstackServiceProvider::class,
                '--tag' => 'migrations',
            ]);
        }

        $this->callSilent('vendor:publish', [
            '--provider' => LitstackServiceProvider::class,
            '--tag' => 'config',
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
        if ($this->files->exists(base_path('lit/app/Kernel.php'))) {
            return;
        }
        $this->info('publishing lit');
        // clear the config cache, otherwise, lit_resource_path() will return
        // the resource path itself, which is present for shure
        $this->callSilent('config:clear');

        $this->files->copyDirectory(
            realpath(lit_vendor_path('publish/lit')),
            base_path('lit')
        );

        $composer = json_decode($this->files->get(base_path('composer.json')), true);
        $composer['autoload']['psr-4']['Lit\\'] = 'lit/app/';
        $this->files->put(base_path('composer.json'), json_encode(
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
