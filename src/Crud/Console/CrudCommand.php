<?php

namespace Ignite\Crud\Console;

use Ignite\Support\StubBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:crud {--model=} {--media=} {--translatable=} {--slug=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This wizard will generate all the files needed for a new crud module';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('    __     _  __     ______ ____   __  __ ____  ');
        $this->info('   / /    (_)/ /_   / ____// __ \ / / / // __ \ ');
        $this->info('  / /    / // __/  / /    / /_/ // / / // / / / ');
        $this->info(' / /___ / // /_   / /___ / _, _// /_/ // /_/ /  ');
        $this->info('/_____//_/ \__/   \____//_/ |_| \____//_____/   ');
        $this->info('                                                ');

        $modelName = $this->option('model');
        if (! $modelName) {
            $modelName = $this->ask('enter the model name (PascalCase, singular)');
        }

        $modelName = ucfirst(Str::singular($modelName));

        $m = $this->option('media');
        $s = $this->option('slug');
        $t = $this->option('translatable');
        if ($m !== '0') {
            $m = $this->choice('does the model have media?', ['y', 'n'], 0) == 'y' ? true : false;
        }
        if ($s !== '0') {
            $s = $this->choice('does the model have a slug?', ['y', 'n'], 0) == 'y' ? true : false;
        }
        if ($t !== '0') {
            $t = $this->choice('is the model translatable?', ['y', 'n'], 0) == 'y' ? true : false;
        }

        $this->translatable = $t;
        $this->sluggable = $s;
        $this->media = $m;

        $translationModelPath = $this->makeModel($modelName, $m, $s, $t);
        $migrationFileName = $this->makeMigration($modelName, $s, $t);
        $this->makeController($modelName);
        $this->makeConfig($modelName);

        $litConfigPath = 'lit/app/Config/';

        $step = 1;
        $this->info("\n----- finished -----\n");
        $this->line("<info>{$step}) Migration:</info> Edit the generated migration in<info> {$migrationFileName} </info>and migrate after.");
        $step++;
        $this->line("<info>{$step}) Model:</info> Set the fillable fields in <info>app/Models/{$modelName}.php</info>");
        $step++;
        if ($t) {
            $this->line("<info>{$step}) Translation Model:</info> Set the fillable fields as well in <info>app/Models/Translations/{$modelName}Translation.php</info>");
            $step++;
        }
        $this->line("<info>{$step}) Config:</info> Configure the crud-model in <info>{$litConfigPath}Crud/{$modelName}Config.php</info>");
        $step++;
        $this->line("<info>{$step}) Controller:</info> configure the authorization in <info>lit/app/Controllers/Crud/{$modelName}Controller.php</info>");
        $step++;
        $this->line("<info>{$step}) Navigation:</info> add a navigation entry in <info>{$litConfigPath}NavigationConfig.php</info>");
    }

    private function makeModel($modelName, $m, $s, $t)
    {
        $model = app_path('Models/'.$modelName.'.php');

        if (\File::exists($model)) {
            $this->line('Model App\\Models\\'.$modelName.' already exists.');

            return;
        }

        $implements = [];
        $uses = [];
        $appends = [];
        $with = [];

        $builder = new StubBuilder(lit_vendor_path('stubs/crud.model.stub'));

        $builder->withClassname($modelName);

        // getRoute routename
        $builder->withRoutename(Str::snake(Str::plural($modelName)));

        // model has media
        if ($m) {
            $builder->withTraits("use Spatie\MediaLibrary\HasMedia as HasMediaContract;");
            $builder->withTraits("use Ignite\Crud\Models\Traits\HasMedia;");

            $attributeContents = file_get_contents(lit_vendor_path('stubs/crud.model.media.attribute.stub'));
            $builder->withGetAttributes($attributeContents);

            $implements[] = 'HasMediaContract';
            $uses[] = 'HasMedia';
            $appends[] = 'image';
            $with[] = 'media';
        }

        // model has slug
        if ($s) {
            // if is not translated
            if (! $t) {
                $builder->withTraits("use Ignite\Crud\Models\Traits\Sluggable;");

                $sluggableContents = file_get_contents(lit_vendor_path('stubs/crud.model.sluggable.stub'));
                $builder->withSluggable($sluggableContents);

                $uses[] = 'Sluggable';
            }
        }

        // model is translatable
        if ($this->translatable) {
            $builder->withTraits("use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;");
            $builder->withTraits("use Ignite\Crud\Models\Traits\Translatable;");
            $builder->withVars('
    /**
     * Translated attributes.
     *
     * @var array
     */
    public $translatedAttributes = [\'title\', \'text\'];');

            $attributeContents = file_get_contents(lit_vendor_path('stubs/crud.model.translation.attribute.stub'));
            $builder->withGetAttributes($attributeContents);

            $implements[] = 'TranslatableContract';
            $uses[] = 'Translatable';
            $appends[] = 'translation';
            $with[] = 'translations';

            $this->makeTranslationModel($modelName, $s);
        }

        if ($implements) {
            $builder->withImplement('implements '.implode(', ', $implements));
        }

        if ($uses) {
            $builder->withUses('use '.implode(', ', $uses).';');
        }

        if ($appends) {
            $builder->withVars('
    /** 
     * The accessors to append to the model\'s array form.
     *
     * @var array
     */
    protected $appends = [\''.implode("', '", $appends).'\'];');
        }

        if ($with) {
            $builder->withWiths('
    /** 
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [\''.implode("', '", $with).'\'];');
        }

        $builder->create($model);

        $this->info('model created');
    }

    private function makeTranslationModel($modelName, $s)
    {
        $model = app_path('Models/Translations/'.$modelName.'Translation.php');

        if (File::exists($model)) {
            $this->line('Translation Model already exists.');

            return;
        }

        if (! file_exists($model)) {
            $fileContents = file_get_contents(__DIR__.'/../../stubs/CrudTranslationModel.stub');

            $fileContents = str_replace('DummyClassname', $modelName.'Translation', $fileContents);

            // if the model is sluggable, add sluggable trait
            if ($s) {
                $fileContents = str_replace('DummyTraits', "use Ignite\Crud\Models\Traits\Sluggable;\nDummyTraits", $fileContents);
                $fileContents = str_replace('DummyTraits', "use Illuminate\Database\Eloquent\Builder;\nDummyTraits", $fileContents);

                $sluggableContents = file_get_contents(__DIR__.'/../../stubs/crud.model.sluggable.stub');
                $fileContents = str_replace('DummySluggable', $sluggableContents."\n".'DummySluggable', $fileContents);

                $sluggableContents = file_get_contents(__DIR__.'/../../stubs/CrudTranslationModelSlugUnique.stub');
                $fileContents = str_replace('DummySluggable', $sluggableContents, $fileContents);

                $uses = ['Sluggable'];
                $fileContents = $this->makeUses($uses, $fileContents);
            }

            // remove placeholders
            $fileContents = $this->cleanUp($fileContents);

            if (! \File::exists('app/Models/Translations')) {
                \File::makeDirectory('app/Models/Translations');
            }
            if (\File::put($model, $fileContents)) {
                $this->info('translation model created');
            }
        } else {
            $this->error('translation-model already exists');
        }
    }

    protected function makeMigration($modelName, $s, $t)
    {
        $tableName = Str::snake(Str::plural($modelName));
        $translationTableName = Str::singular($tableName).'_translations';

        $files = scandir(base_path('database/migrations'));
        $migrationName = 'create_'.$tableName.'_table.php';
        $timestamp = str_replace(' ', '_', str_replace('-', '_', str_replace(':', '', now())));
        $migrationFileName = "database/migrations/{$timestamp}_create_{$tableName}_table.php";
        foreach ($files as $file) {
            if (Str::endsWith($file, $migrationName)) {
                $this->line('Migration for '.$tableName.' already exists.');

                return $migrationFileName;
            }
        }

        $fileContents = File::get($this->getMigrationStubPath());

        $replace = [
            'DummyTranslationTablename' => $translationTableName,
            'DummyForeignId'            => Str::singular($tableName).'_id',
            'DummyClassname'            => 'Create'.ucfirst(Str::plural($modelName)).'Table',
            'DummyTablename'            => $tableName,
        ];

        if ($this->sluggable) {
            $replace['DummySlug'] = "\n\t\t\t\$table->string('slug')->nullable();\n";
        } else {
            $fileContents = str_replace('DummySlug', '', $fileContents);
        }

        foreach ($replace as $name => $value) {
            $fileContents = str_replace($name, $value, $fileContents);
        }

        if (File::put($migrationFileName, $fileContents)) {
            $this->info('migration created');
        }

        return $migrationFileName;
    }

    /**
     * GEt nigration stub path.
     *
     * @return void
     */
    protected function getMigrationStubPath()
    {
        $path = __DIR__.'/../../stubs/crud.migration';

        return $this->translatable ? "$path.translatable.stub" : "{$path}.stub";
    }

    protected function makeController($modelName)
    {
        $tableName = Str::snake(Str::plural($modelName));

        $controllerPath = base_path('lit/app/Controllers/Crud/'.$modelName.'Controller.php');

        if (\File::exists($controllerPath)) {
            $this->line("Controller {$modelName}Controller already exists.");

            return;
        }

        $fileContents = file_get_contents(__DIR__.'/../../stubs/CrudController.stub');

        $fileContents = str_replace('DummyClass', $modelName.'Controller', $fileContents);
        $fileContents = str_replace('DummyModelClass', "\\App\\Models\\{$modelName}", $fileContents);
        $fileContents = str_replace('DummyTableName', $tableName, $fileContents);

        if (! \File::exists(base_path('lit/app/Controllers/Crud'))) {
            \File::makeDirectory(base_path('lit/app/Controllers/Crud'));
        }
        if (\File::put($controllerPath, $fileContents)) {
            $this->info('Controller created.');
        }
    }

    protected function makeConfig($modelName)
    {
        $tableName = Str::snake(Str::plural($modelName));
        $config = base_path('lit/app/Config/Crud/'.ucfirst($modelName).'Config.php');

        $name = ucfirst($modelName);
        if (\File::exists($config)) {
            $this->line("Controller {$name}Controller already exists.");

            return;
        }

        $fileContents = file_get_contents(__DIR__.'/../../stubs/CrudConfig.stub');
        $fileContents = str_replace('DummyClassname', $modelName, $fileContents);
        $fileContents = str_replace('DummyTablename', $tableName, $fileContents);
        if (! \File::exists(base_path('lit/app/Config/Crud'))) {
            \File::makeDirectory(base_path('lit/app/Config/Crud'));
        }
        if (\File::put($config, $fileContents)) {
            $this->info('config created');
        }
    }

    protected function cleanUp($fileContents)
    {
        $fileContents = str_replace('DummyTraits', '', $fileContents);
        $fileContents = str_replace('DummyUses', '', $fileContents);
        $fileContents = str_replace('DummyVars', '', $fileContents);
        $fileContents = str_replace('DummySluggable', '', $fileContents);
        $fileContents = str_replace('DummyGetAttributes', '', $fileContents);
        $fileContents = str_replace('DummyImplement', '', $fileContents);

        return $fileContents;
    }

    protected function makeImplements($implements, $fileContents)
    {
        // model implements…
        if (count($implements) > 0) {
            $delimiter = '';
            $str = 'implements ';
            foreach ($implements as $imp) {
                $str .= $delimiter.$imp;
                $delimiter = ', ';
            }
            $fileContents = str_replace('DummyImplement', $str, $fileContents);
        }

        return $fileContents;
    }

    protected function makeUses($uses, $fileContents)
    {
        // model uses traits:
        if (count($uses) > 0) {
            $delimiter = '';
            $str = 'use ';
            foreach ($uses as $use) {
                $str .= $delimiter.$use;
                $delimiter = ', ';
            }
            $fileContents = str_replace('DummyUses', $str.';', $fileContents);
        }

        return $fileContents;
    }

    protected function makeAppends($appends, $fileContents)
    {
        // model appends:
        if (count($appends) > 0) {
            $delimiter = '';
            $str = '
       /** 
        * The accessors to append to the model\'s array form.
        *
        * @var array
        */
        protected \$appends = [';
            foreach ($appends as $append) {
                $str .= $delimiter."'".$append."'";
                $delimiter = ', ';
            }
            $fileContents = str_replace('DummyVars', $str.'];', $fileContents);
        }

        dd($fileContents);

        return $fileContents;
    }
}
