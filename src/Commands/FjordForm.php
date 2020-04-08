<?php

namespace Fjord\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Fjord\Filesystem\StubBuilder;
use Illuminate\Support\Facades\File;

class FjordForm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create controller and config file for a new form.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("    ______ _                   __   ______ ____   __  __ ____  ");
        $this->info("   / ____/(_)____   _____ ____/ /  / ____// __ \ / / / // __ \ ");
        $this->info("  / /_   / // __ \ / ___// __  /  / /    / /_/ // / / // / / / ");
        $this->info(" / __/  / // /_/ // /   / /_/ /  / /___ / _, _// /_/ // /_/ /  ");
        $this->info("/_/  __/ / \____//_/    \__,_/   \____//_/ |_| \____//_____/   ");
        $this->info("    /___/                                                      ");

        $collection = $this->ask('enter the collection name (snake_case, plural)');
        $formName = $this->ask('enter the form name (snake_case)');

        $collection = Str::snake($collection);
        $formName = Str::snake($formName);

        $controllerNamespace = ucfirst(Str::camel(Str::singular($collection)));
        $controllerName = ucfirst(Str::camel($formName));

        $controllerDir = app_path("Http/Controllers/Fjord/Form/{$controllerNamespace}");
        $controller = new StubBuilder(fjord_path('stubs/FormController.stub'));
        $controller->withClassname("{$controllerName}Controller");
        $controller->withNamespace($controllerNamespace);
        $controller->withPermission("{$collection}");

        $configDir = fjord_resource_path("forms/{$collection}");
        $config = new StubBuilder(fjord_path('stubs/FormConfig.stub'));
        $config->withCollection($controllerNamespace);
        $config->withFormName($controllerName);
        $config->withController("{$controllerName}Controller");

        $this->createDirIfNotExists($configDir);
        $this->createDirIfNotExists($controllerDir);

        $controller->create("{$controllerDir}/{$controllerName}Controller.php");
        $config->create("{$configDir}/{$formName}.php");
    }

    /**
     * Create directory if not exists.
     *
     * @param string $dir
     * @return void
     */
    public function createDirIfNotExists(string $dir)
    {
        if (!File::exists($dir)) {
            File::makeDirectory($dir);
        }
    }
}
