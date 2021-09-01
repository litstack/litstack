<?php

namespace Ignite\Crud\Console\Concerns;

use Ignite\Support\StubBuilder;

trait CreatesModels
{
    /**
     * Create Model file.
     *
     * @return bool
     */
    protected function createModel()
    {
        if ($this->modelExists()) {
            $this->line("Model {$this->model} already exists.");

            return false;
        }

        $implements = [];
        $uses = [];
        $appends = [];
        $with = [];

        $builder = new StubBuilder($this->modelStubPath());

        $builder->withClassname($this->model);

        if ($this->factory) {
            $builder->withTraits("use Illuminate\Database\Eloquent\Factories\HasFactory;");
            $uses[] = 'HasFactory';

            $this->createFactory();
        }

        // model has media
        if ($this->media) {
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
        if ($this->slug && ! $this->translatable) {
            $builder->withTraits("use Ignite\Crud\Models\Traits\Sluggable;");

            $sluggableContents = $this->files->get($this->modelSluggableStubPath());
            $builder->withSluggable($sluggableContents);

            $uses[] = 'Sluggable';
        }

        // model is translatable
        if ($this->translatable) {
            $builder->withTraits("use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;");
            $builder->withTraits("use Ignite\Crud\Models\Traits\Translatable;");
            $builder->withVars('
    /**
     * The attributes to be translated.
     *
     * @var array
     */
    public $translatedAttributes = [\'title\'];');

            // $attributeContents = file_get_contents(lit_vendor_path('stubs/crud.model.translation.attribute.stub'));
            // $builder->withGetAttributes($attributeContents);

            $implements[] = 'TranslatableContract';
            $uses[] = 'Translatable';
            // $appends[] = 'translation';
            $with[] = 'translations';

            $this->createTranslationModel();
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

        $builder->create($this->modelPath());
        fix_file($this->modelPath());

        $this->info('Model created!');

        return $this;
    }

    /**
     * Create translation model.
     *
     * @return bool
     */
    protected function createTranslationModel()
    {
        if ($this->files->exists($this->translationModelPath())) {
            $this->line('Translation Model already exists.');

            return false;
        }

        $content = file_get_contents($this->translationModelStubPath());

        $content = str_replace('DummyClassname', $this->model.'Translation', $content);

        // if the model is sluggable, add sluggable trait
        if ($this->slug) {
            $content = str_replace('DummyTraits', "use Ignite\Crud\Models\Traits\Sluggable;\nDummyTraits", $content);
            $content = str_replace('DummyTraits', "use Illuminate\Database\Eloquent\Builder;\nDummyTraits", $content);

            $sluggableContents = file_get_contents($this->sluggableModelStubPath());
            $content = str_replace('DummySluggable', $sluggableContents."\n".'DummySluggable', $content);

            $sluggableContents = file_get_contents($this->translationModelSlugUniqueStubPath());
            $content = str_replace('DummySluggable', $sluggableContents, $content);

            $uses = ['Sluggable'];
            // Model uses traits:
            if (count($uses) > 0) {
                $delimiter = '';
                $str = 'use ';
                foreach ($uses as $use) {
                    $str .= $delimiter.$use;
                    $delimiter = ', ';
                }
                $content = str_replace('DummyUses', $str.';', $content);
            }
        }

        // Remove placeholders.
        $content = str_replace([
            'DummyTraits', 'DummyUses', 'DummyVars', 'DummySluggable',
            'DummyGetAttributes', 'DummyImplement',
        ], '', $content);

        $this->files->ensureDirectoryExists(dirname($this->translationModelPath()));
        $this->files->put($this->translationModelPath(), $content);
        fix_file($this->translationModelPath());

        $this->info('Translation model created!');

        return true;
    }

    /**
     * Calls the factory generator Command.
     *
     * @return bool
     */
    public function createFactory()
    {
        if ($this->files->exists($this->modelFactoryPath())) {
            $this->line('Model factory already exists.');

            return false;
        }

        $this->callSilent('make:factory', [
            'name' => $this->model.'Factory',
            '--model' => $this->model,
        ]);

        $this->info('Model factory created!');

        return true;
    }

    /**
     * Determines if the model file already exists.
     *
     * @return bool
     */
    protected function modelExists()
    {
        return $this->files->exists($this->modelPath());
    }
}
