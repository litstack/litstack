<?php

namespace Fjord\Commands;

use Illuminate\Console\Command;
use Fjord\Crud\Config\CrudConfig;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Fjord\Commands\Traits\RolesAndPermissions;
use FjordApp\Config\User\ProfileSettingsConfig;
use FjordApp\Controllers\User\ProfileSettingsController;

class FjordUpgrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will upgrade your Fjord version from ^2.2 to ^2.3';

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
        $this->info("\n----- start -----\n");
        $this->call('fjord:guard');

        $this->update();
        $this->line('');
        $this->info("Upgrade perared. Now there are only a few steps left.\n");
        $this->info('Read ' . fjord_path('UPGRADING.md') . ' to do the rest.');
    }

    public function update()
    {
        // Config files.
        if (!new ProfileSettingsConfig instanceof CrudConfig) {
            $this->line('Replaced ' . base_path('fjord/app/Config/User/ProfileSettingsConfig.php'));
            $this->line('Replaced ' . base_path('fjord/app/Config/User/UserIndexConfig.php'));
            File::copy(fjord_path('publish/fjord/app/Config/User/ProfileSettingsConfig.php'), base_path('fjord/app/Config/User/ProfileSettingsConfig.php'));
            File::copy(fjord_path('publish/fjord/app/Config/User/UserIndexConfig.php'), base_path('fjord/app/Config/User/UserIndexConfig.php'));
        }

        // Controller
        if (!File::exists(base_path('fjord/app/Controllers/User'))) {
            $this->line('Published ' . fjord_path('publish/fjord/app/Controllers/User/ProfileSettingsController.php'));
            $this->line('Published ' . fjord_path('publish/fjord/app/Controllers/User/UserIndexController.php'));
            File::copyDirectory(fjord_path('publish/fjord/app/Controllers/User'), base_path('fjord/app/Controllers/User'));
        }

        // Crud Config
        $files = glob(base_path('fjord/app/Config/Crud/*.php'));



        foreach ($files as $file) {
            $content = file_get_contents($file);
            $content = str_replace('form(CrudForm $form)', 'show(CrudShow $form)', $content);
            $content = str_replace('Fjord\Crud\CrudForm', 'Fjord\Crud\CrudShow', $content);
            $content = str_replace('index(CrudTable $table)', 'index(CrudIndex $container)', $content);
            $content = str_replace('Fjord\Vue\Crud\CrudTable', 'Fjord\Crud\CrudIndex', $content);
            file_put_contents($file, $content);
        }
        $this->line('Fixed crud config namespaces.');

        // Form Config
        $files = File::allFiles(base_path('fjord/app/Config/Form'));

        foreach ($files as $file) {
            if ($file->isDir()) continue;
            if (!\Str::contains($file, '.php')) continue;
            $content = file_get_contents($file);
            $content = str_replace('form(CrudForm $form)', 'show(CrudShow $form)', $content);
            $content = str_replace('Fjord\Crud\CrudForm', 'Fjord\Crud\CrudShow', $content);
            file_put_contents($file, $content);
        }
        $this->line('Fixed form config namespaces.');

        // Navigation
        $path = base_path('fjord/app/Config/NavigationConfig.php');
        $content = file_get_contents($path);
        $content = str_replace("preset('pages", "preset('form.pages", $content);
        $content = str_replace("preset('collections", "preset('form.collections", $content);
        $content = str_replace("\$nav->preset('users')", "\$nav->preset('user.user_index', [
                'icon' => fa('users')
            ])", $content);
        file_put_contents($path, $content);

        $this->line('Updated Navigation presets.');
    }
}
