<?php

namespace AwStudio\Fjord\Commands;

use Illuminate\Console\Command;

class FjordCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:crud';

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
        $modelName = ucfirst(str_singular($this->ask('enter the model name')));
        $m = $this->choice('does the model have media?', ['y', 'n'], 0) == 'y' ? true : false;
        $s = $this->choice('does the model have a slug?', ['y', 'n'], 0) == 'y' ? true : false;
        $t = $this->choice('does the model need to be translated?', ['y', 'n'], 0) == 'y' ? true : false;

        $this->makeModel($modelName, $m, $s, $t);
        $this->makeMigration($modelName, $s, $t);
        $this->makeController($modelName);
        $this->makeConfig($modelName);

        $this->info("\n----- finished -----\n");
        $this->info('1) edit the generated migration and migrate');
        $this->info('2) set the fillable fields in your model(s)');
        $this->info('3) make your model editable by adding it to the config/fjord-crud.php');
        $this->info('4) add a navigation entry to your config/fjord-navigation.php');

    }

    private function makeModel($modelName, $m, $s, $t)
    {
        $model = app_path('Models/'.$modelName.'.php');

        $implements = [];
        $uses = [];
        $appends = [];

        if (!file_exists($model) ) {
            $fileContents = file_get_contents(__DIR__.'/../../stubs/CrudModel.stub');

            if ($fileContents !== false) {
                $fileContents = str_replace('DummyClassname', $modelName, $fileContents);

                // model has media
                if($m){
                    $fileContents = str_replace('DummyTraits', "use Spatie\MediaLibrary\HasMedia\HasMedia;\nDummyTraits", $fileContents);
                    $fileContents = str_replace('DummyTraits', "use Spatie\MediaLibrary\HasMedia\HasMediaTrait;\nDummyTraits", $fileContents);
                    //$fileContents = str_replace('DummyVars', 'protected $appends'." = ['image'];\n    DummyVars", $fileContents);

                    $attributeContents = file_get_contents(__DIR__.'/../../stubs/CrudModelMediaAttribute.stub');
                    $fileContents = str_replace('DummyGetAttributes', $attributeContents."\n    DummyGetAttributes", $fileContents);

                    $implements []= 'HasMedia';
                    $uses []= 'HasMediaTrait';
                    $appends []= 'image';
                }

                // model has slug
                if($s){
                    // if is not translated
                    if(!$t){
                        $fileContents = str_replace('DummyTraits', "use Cviebrock\EloquentSluggable\Sluggable;\nDummyTraits", $fileContents);

                        $sluggableContents = file_get_contents(__DIR__.'/../../stubs/CrudModelSluggable.stub');
                        $fileContents = str_replace('DummySluggable', $sluggableContents, $fileContents);

                        $uses []= 'Sluggable';
                    }
                }

                // model is translatable
                if($t){
                    $fileContents = str_replace('DummyTraits', "use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;\nDummyTraits", $fileContents);
                    $fileContents = str_replace('DummyTraits', "use Astrotomic\Translatable\Translatable;\nDummyTraits", $fileContents);
                    $fileContents = str_replace('DummyVars', 'public $translatedAttributes'." = ['title', 'text'];\n    DummyVars", $fileContents);

                    $attributeContents = file_get_contents(__DIR__.'/../../stubs/CrudModelTranslationAttribute.stub');
                    $fileContents = str_replace('DummyGetAttributes', $attributeContents."\n    DummyGetAttributes", $fileContents);

                    $implements []= 'TranslatableContract';
                    $uses []= 'Translatable';
                    $appends []= 'translation';

                    $this->makeTranslationModel($modelName, $s);
                }


                $fileContents = $this->makeImplements($implements, $fileContents);
                $fileContents = $this->makeUses($uses, $fileContents);
                $fileContents = $this->makeAppends($appends, $fileContents);

                // remove placeholders
                $fileContents = $this->cleanUp($fileContents);
            }else{
                $this->error('template not found');
            }

            if(\File::put($model, $fileContents)){
                $this->info('model created');
            }
        }else{
            $this->error('model already exists');
        }
    }

    private function makeTranslationModel($modelName, $s)
    {
        $model = app_path('Models/Translations/'.$modelName.'Translation.php');

        if (!file_exists($model) ) {
            $fileContents = file_get_contents(__DIR__.'/../../stubs/CrudTranslationModel.stub');

            $fileContents = str_replace('DummyClassname', $modelName.'Translation', $fileContents);

            // if the model is sluggable, add sluggable trait
            if($s){
                $fileContents = str_replace('DummyTraits', "use Cviebrock\EloquentSluggable\Sluggable;\nDummyTraits", $fileContents);

                $sluggableContents = file_get_contents(__DIR__.'/../../stubs/CrudModelSluggable.stub');
                $fileContents = str_replace('DummySluggable', $sluggableContents, $fileContents);

                $uses = ['Sluggable'];
                $fileContents = $this->makeUses($uses, $fileContents);
            }

            // remove placeholders
            $fileContents = $this->cleanUp($fileContents);

            if(!\File::exists('app/Models/Translations')){
                \File::makeDirectory('app/Models/Translations');
            }
            if(\File::put($model, $fileContents)){
                $this->info('model created');
            }
        }else{
            $this->error('translation-model already exists');
        }
    }

    private function makeMigration($modelName, $s, $t)
    {
        $fileContents = file_get_contents(__DIR__.'/../../stubs/CrudMigration.stub');

        // model is translatable
        if($t){
            $translationContents = file_get_contents(__DIR__.'/../../stubs/CrudMigrationTranslation.stub');
            $fileContents = str_replace('DummyTranslation', $translationContents, $fileContents);
            $fileContents = str_replace('DummyDownTranslation', "Schema::dropIfExists('DummyTranslationTablename');", $fileContents);
            $fileContents = str_replace('DummyTranslationTablename', lcfirst($modelName) . '_translations', $fileContents);
            $fileContents = str_replace('DummyForeignId', lcfirst($modelName) . '_id', $fileContents);
        }else{
            $fileContents = str_replace('DummyTranslation', '', $fileContents);
            $fileContents = str_replace('DummyTranslationDown', '', $fileContents);
        }

        // model has slug
        if($s){
            $fileContents = str_replace('DummySlug', '$table->string'."('slug')->nullable();", $fileContents);
        }else{
            $fileContents = str_replace('DummySlug', '', $fileContents);
        }

        $fileContents = str_replace('DummyClassname', "Create".ucfirst(str_plural($modelName))."Table", $fileContents);
        $fileContents = str_replace('DummyTablename', lcfirst(str_plural($modelName)), $fileContents);



        $timestamp = str_replace(' ', '_', str_replace('-', '_', str_replace(':', '', now())));
        if(\File::put('database/migrations/'.$timestamp.'_create_'.str_plural(lcfirst($modelName)).'_table.php', $fileContents)){
            $this->info('migration created');
        }
    }

    private function makeController($modelName)
    {
        $controller = app_path('Http/Controllers/Fjord/'.$modelName.'Controller.php');

        $fileContents = file_get_contents(__DIR__.'/../../stubs/CrudController.stub');

        $fileContents = str_replace('DummyClassname', $modelName . 'Controller', $fileContents);
        $fileContents = str_replace('DummyModelName', $modelName, $fileContents);

        if(!\File::exists('app/Http/Controllers/Fjord')){
            \File::makeDirectory('app/Http/Controllers/Fjord');
        }
        if(\File::put($controller, $fileContents)){
            $this->info('controller created');
        }
    }

    private function makeConfig($modelName)
    {
        $config = fjord_resource_path('crud/'.strtolower(str_plural($modelName)).'.php');

        $fileContents = file_get_contents(__DIR__.'/../../stubs/CrudConfig.stub');
        $fileContents = str_replace('DummyClassname', $modelName, $fileContents);

        if(\File::put($config, $fileContents)){
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
        if(count($implements) > 0){
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
        if(count($uses) > 0){
            $delimiter = '';
            $str = 'use ';
            foreach ($uses as $use) {
                $str .= $delimiter . $use;
                $delimiter = ', ';
            }
            $fileContents = str_replace('DummyUses', $str.';', $fileContents);
        }

        return $fileContents;
    }

    private function makeAppends($appends, $fileContents)
    {
        // model appends:
        if(count($appends) > 0){
            $delimiter = '';
            $str = 'protected $appends = [';
            foreach ($appends as $append) {
                $str .= $delimiter . "'" . $append . "'";
                $delimiter = ', ';
            }
            $fileContents = str_replace('DummyVars', $str.'];', $fileContents);
        }

        return $fileContents;
    }


}
