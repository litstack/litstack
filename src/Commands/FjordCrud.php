<?php

namespace Fjord\Commands;

use Fjord\Support\StubBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FjordCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:crud {--model=} {--media=} {--translatable=} {--slug=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This wizard will generate all the files needed for a new crud module';

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
    public function handle()
    {
        $this->info("    ______ _                   __   ______ ____   __  __ ____  ");
        $this->info("   / ____/(_)____   _____ ____/ /  / ____// __ \ / / / // __ \ ");
        $this->info("  / /_   / // __ \ / ___// __  /  / /    / /_/ // / / // / / / ");
        $this->info(" / __/  / // /_/ // /   / /_/ /  / /___ / _, _// /_/ // /_/ /  ");
        $this->info("/_/  __/ / \____//_/    \__,_/   \____//_/ |_| \____//_____/   ");
        $this->info("    /___/                                                      ");

        $modelName = $this->option('model');
        if (!$modelName) {
            $modelName = $this->ask('enter the model name (PascalCase, singular)');
        }

        $modelName = ucfirst(Str::singular($modelName));

        $m = $this->option('media');
        $s = $this->option('slug');
        $t = $this->option('translatable');
        if ($m !== "0") {
            $m = $this->choice('does the model have media?', ['y', 'n'], 0) == 'y' ? true : false;
        }
        if ($s !== "0") {
            $s = $this->choice('does the model have a slug?', ['y', 'n'], 0) == 'y' ? true : false;
        }
        if ($t !== "0") {
            $t = $this->choice('is the model translatable?', ['y', 'n'], 0) == 'y' ? true : false;
        }

        $translationModelPath = $this->makeModel($modelName, $m, $s, $t);
        $migrationFileName = $this->makeMigration($modelName, $s, $t);
        $this->makeController($modelName);
        $this->makeConfig($modelName);

        $fjordConfigPath = 'fjord/app/Config/';

        $step = 1;
        $this->info("\n----- finished -----\n");
        $this->info("{$step}) edit the generated migration and migrate in {$migrationFileName}");
        $step++;
        $this->info("{$step}) set the fillable fields in your model in app/Models/{$modelName}.php");
        $step++;
        if ($t) {
            $this->info("{$step}) set the fillable fields in your translation model in app/Models/Translations/{$modelName}Translation.php");
            $step++;
        }
        $this->info("{$step}) configure the crud-model in {$fjordConfigPath}Crud/{$modelName}Config.php");
        $step++;
        $this->info("{$step}) configure the authorization in fjord/app/Controllers/Crud/{$modelName}Controller.php");
        $step++;
        $this->info("{$step}) add a navigation entry in {$fjordConfigPath}NavigationConfig.php");
    }

    private function makeModel($modelName, $m, $s, $t)
    {
        $model = app_path('Models/' . $modelName . '.php');

        if (\File::exists($model)) {
            $this->info('Model App\\Models\\' . $modelName . ' already exists.');
            return;
        }

        $implements = [];
        $uses = ['TrackEdits'];
        $appends = [];
        $with = [];

        if (file_exists($model)) {
            $this->error('model already exists');
        }

        $builder = new StubBuilder(fjord_path('stubs/CrudModel.stub'));

        $builder->withClassname($modelName);

        // getRoute routename
        $builder->withRoutename(Str::snake(Str::plural($modelName)));

        $builder->withTraits("use Fjord\Crud\Models\Traits\TrackEdits;");

        // model has media
        if ($m) {
            $builder->withTraits("use Spatie\MediaLibrary\HasMedia as HasMediaContract;");
            $builder->withTraits("use Fjord\Crud\Models\Traits\HasMedia;");

            $attributeContents = file_get_contents(fjord_path('stubs/CrudModelMediaAttribute.stub'));
            $builder->withGetAttributes($attributeContents);

            $implements[] = 'HasMediaContract';
            $uses[] = 'HasMedia';
            $appends[] = 'image';
            $with[] = 'media';
        }

        // model has slug
        if ($s) {
            // if is not translated
            if (!$t) {
                $builder->withTraits("use Fjord\Crud\Models\Traits\Sluggable;");

                $sluggableContents = file_get_contents(fjord_path('stubs/CrudModelSluggable.stub'));
                $builder->withSluggable($sluggableContents);

                $uses[] = 'Sluggable';
            }
        }

        // model is translatable
        if ($t) {
            $builder->withTraits("use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;");
            $builder->withTraits("use Fjord\Crud\Models\Traits\Translatable;");
            $builder->withVars('public $translatedAttributes' . " = ['title', 'text'];");

            $attributeContents = file_get_contents(fjord_path('stubs/CrudModelTranslationAttribute.stub'));
            $builder->withGetAttributes($attributeContents);

            $implements[] = 'TranslatableContract';
            $uses[] = 'Translatable';
            $appends[] = 'translation';
            $with[] = 'translations';

            $this->makeTranslationModel($modelName, $s);
        }

        if ($implements) {
            $builder->withImplement('implements ' . implode(', ', $implements));
        }

        if ($uses) {
            $builder->withUses('use ' . implode(', ', $uses) . ';');
        }

        if ($appends) {
            $builder->withVars("\tprotected \$appends = ['" . implode("', '", $appends) . "'];");
        }

        if ($with) {
            $builder->withWiths("protected \$with = ['" . implode("', '", $with) . "'];");
        }

        $builder->create($model);

        $this->info('model created');
    }

    private function makeTranslationModel($modelName, $s)
    {
        $model = app_path('Models/Translations/' . $modelName . 'Translation.php');

        if (\File::exists($model)) {
            $this->info("Translation Model already exists.");
            return;
        }

        if (!file_exists($model)) {
            $fileContents = file_get_contents(__DIR__ . '/../../stubs/CrudTranslationModel.stub');

            $fileContents = str_replace('DummyClassname', $modelName . 'Translation', $fileContents);

            // if the model is sluggable, add sluggable trait
            if ($s) {
                $fileContents = str_replace('DummyTraits', "use Fjord\Crud\Models\Traits\Sluggable;\nDummyTraits", $fileContents);
                $fileContents = str_replace('DummyTraits', "use Illuminate\Database\Eloquent\Builder;\nDummyTraits", $fileContents);

                $sluggableContents = file_get_contents(__DIR__ . '/../../stubs/CrudModelSluggable.stub');
                $fileContents = str_replace('DummySluggable', $sluggableContents . "\n" . 'DummySluggable', $fileContents);

                $sluggableContents = file_get_contents(__DIR__ . '/../../stubs/CrudTranslationModelSlugUnique.stub');
                $fileContents = str_replace('DummySluggable', $sluggableContents, $fileContents);

                $uses = ['Sluggable'];
                $fileContents = $this->makeUses($uses, $fileContents);
            }

            // remove placeholders
            $fileContents = $this->cleanUp($fileContents);

            if (!\File::exists('app/Models/Translations')) {
                \File::makeDirectory('app/Models/Translations');
            }
            if (\File::put($model, $fileContents)) {
                $this->info('translation model created');
            }
        } else {
            $this->error('translation-model already exists');
        }
    }

    private function makeMigration($modelName, $s, $t)
    {
        $tableName = Str::snake(Str::plural($modelName));
        $translationTableName = Str::singular($tableName) . '_translations';

        $files = scandir(base_path('database/migrations'));
        $migrationName = 'create_' . $tableName . '_table.php';
        foreach ($files as $file) {
            if (Str::endsWith($file, $migrationName)) {
                $this->info('Migration for ' . $tableName . ' already exists.');
                return;
            }
        }

        $fileContents = file_get_contents(__DIR__ . '/../../stubs/CrudMigration.stub');

        // model is translatable
        if ($t) {
            $translationContents = file_get_contents(__DIR__ . '/../../stubs/CrudMigrationTranslation.stub');
            $fileContents = str_replace('DummyTranslation', $translationContents, $fileContents);
            $fileContents = str_replace('DummyDownTranslation', "Schema::dropIfExists('DummyTranslationTablename');", $fileContents);
            $fileContents = str_replace('DummyTranslationTablename', $translationTableName, $fileContents);
            $fileContents = str_replace('DummyForeignId', Str::singular($tableName) . '_id', $fileContents);
        } else {
            $fileContents = str_replace('DummyTranslation', '', $fileContents);
            $fileContents = str_replace('DummyDownTranslation', '', $fileContents);
        }

        // model has slug
        if ($s) {
            $fileContents = str_replace('DummySlug', '$table->string' . "('slug')->nullable();", $fileContents);
        } else {
            $fileContents = str_replace('DummySlug', '', $fileContents);
        }


        $fileContents = str_replace('DummyClassname', "Create" . ucfirst(Str::plural($modelName)) . "Table", $fileContents);
        $fileContents = str_replace('DummyTablename', $tableName, $fileContents);

        $timestamp = str_replace(' ', '_', str_replace('-', '_', str_replace(':', '', now())));
        $migrationFileName = "database/migrations/{$timestamp}_create_{$tableName}_table.php";
        if (\File::put($migrationFileName, $fileContents)) {
            $this->info('migration created');
        }

        return $migrationFileName;
    }

    private function makeController($modelName)
    {
        $tableName = Str::snake(Str::plural($modelName));

        $controllerPath = base_path('fjord/app/Controllers/Crud/' . $modelName . 'Controller.php');

        if (\File::exists($controllerPath)) {
            $this->info("Controller {$modelName}Controller already exists.");
            return;
        }

        $fileContents = file_get_contents(__DIR__ . '/../../stubs/CrudController.stub');

        $fileContents = str_replace('DummyClassname', $modelName . 'Controller', $fileContents);
        $fileContents = str_replace('DummyModelClass', "\\App\\Models\\{$modelName}", $fileContents);
        $fileContents = str_replace('DummyTableName', $tableName, $fileContents);

        if (!\File::exists(base_path('fjord/app/Controllers/Crud'))) {
            \File::makeDirectory(base_path('fjord/app/Controllers/Crud'));
        }
        if (\File::put($controllerPath, $fileContents)) {
            $this->info('controller created');
        }
    }

    private function makeConfig($modelName)
    {
        $tableName = Str::snake(Str::plural($modelName));
        $config = base_path('fjord/app/Config/Crud/' . ucfirst($modelName) . 'Config.php');

        $name = ucfirst($modelName);
        if (\File::exists($config)) {
            $this->info("Controller {$name}Controller already exists.");
            return;
        }

        $fileContents = file_get_contents(__DIR__ . '/../../stubs/CrudConfig.stub');
        $fileContents = str_replace('DummyClassname', $modelName, $fileContents);
        $fileContents = str_replace('DummyTablename', $tableName, $fileContents);
        if (!\File::exists(base_path('fjord/app/Config/Crud'))) {
            \File::makeDirectory(base_path('fjord/app/Config/Crud'));
        }
        if (\File::put($config, $fileContents)) {
            $this->info('config created');
        }
    }

    private function cleanUp($fileContents)
    {
        $fileContents = str_replace('DummyTraits', '', $fileContents);
        $fileContents = str_replace('DummyUses', '', $fileContents);
        $fileContents = str_replace('DummyVars', '', $fileContents);
        $fileContents = str_replace('DummySluggable', '', $fileContents);
        $fileContents = str_replace('DummyGetAttributes', '', $fileContents);
        $fileContents = str_replace('DummyImplement', '', $fileContents);

        return $fileContents;
    }

    private function makeImplements($implements, $fileContents)
    {
        // model implements…
        if (count($implements) > 0) {
            $delimiter = '';
            $str = 'implements ';
            foreach ($implements as $imp) {
                $str .= $delimiter . $imp;
                $delimiter = ', ';
            }
            $fileContents = str_replace('DummyImplement', $str, $fileContents);
        }

        return $fileContents;
    }

    private function makeUses($uses, $fileContents)
    {
        // model uses traits:
        if (count($uses) > 0) {
            $delimiter = '';
            $str = 'use ';
            foreach ($uses as $use) {
                $str .= $delimiter . $use;
                $delimiter = ', ';
            }
            $fileContents = str_replace('DummyUses', $str . ';', $fileContents);
        }

        return $fileContents;
    }

    private function makeAppends($appends, $fileContents)
    {
        // model appends:
        if (count($appends) > 0) {
            $delimiter = '';
            $str = 'protected $appends = [';
            foreach ($appends as $append) {
                $str .= $delimiter . "'" . $append . "'";
                $delimiter = ', ';
            }
            $fileContents = str_replace('DummyVars', $str . '];', $fileContents);
        }

        return $fileContents;
    }
}
