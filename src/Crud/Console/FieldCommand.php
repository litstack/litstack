<?php

namespace Ignite\Crud\Console;

use Ignite\Application\Console\Concerns\ManagesGeneration;
use Ignite\Console\GeneratorCommand;
use Illuminate\Support\Str;

class FieldCommand extends GeneratorCommand
{
    use ManagesGeneration;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lit:field';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new custom form field';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Field';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        parent::handle();

        if ((! $this->hasOption('force') ||
             ! $this->option('force')) &&
             $this->componentExists()) {
            $this->error($this->type.' component already exists!');

            return false;
        }

        $this->files->ensureDirectoryExists(
            dirname($path = $this->componentPath())
        );

        $this->files->put(
            $path, $this->buildComponent($this->componentName())
        );

        $this->info($this->type.' component created successfully.');
    }

    /**
     * Build vue component.
     *
     * @param  string $name
     * @return string
     */
    protected function buildComponent($name)
    {
        $replace = [
            'DummyComponent' => $name,
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            $this->files->get($this->getComponentStub())
        );
    }

    /**
     * Determiens if vue component already exists.
     *
     * @return bool
     */
    protected function componentExists()
    {
        return $this->files->exists($this->componentPath());
    }

    /**
     * Get path to vue component.
     *
     * @return string
     */
    protected function componentPath()
    {
        return lit_resource_path('js/components/Fields/'.$this->componentName().'.vue');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getComponentStub()
    {
        return lit_vendor_path('stubs/field.component.stub');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return lit_vendor_path('stubs/field.stub');
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [
            'DummyComponent' => $this->componentName($tag = true),
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Get vue component name.
     *
     * @param  bool   $tag
     * @return string
     */
    protected function componentName(bool $tag = false)
    {
        $name = Str::replaceLast('Field', '', $this->argument('name'));

        if ($tag === true) {
            return Str::slug($name);
        }

        return Str::studly($name);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Fields';
    }
}
