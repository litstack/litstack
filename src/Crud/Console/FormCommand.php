<?php

namespace Ignite\Crud\Console;

use Ignite\Console\GeneratorCommand;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FormCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:form {collection?} {name?}
                            {--collection= : Form collection name }
                            {--form= : Form name}';

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
        $this->info('    __     _  __     ______                       ');
        $this->info('   / /    (_)/ /_   / ____/____   _____ ____ ___  ');
        $this->info('<info>  / /    / // __/  / /_   / __ \ / ___// __ `__ \ ');
        $this->info(' / /___ / // /_   / __/  / /_/ // /   / / / / / / ');
        $this->info('/_____//_/ \__/  /_/     \____//_/   /_/ /_/ /_/  ');
        $this->info('                                                  ');

        $this->setCollectionAndFormName();

        parent::handle();

        $this->makeController();
    }

    /**
     * Make form controller.
     *
     * @return void
     */
    protected function makeController()
    {
        $this->call('lit:controller', [
            'name'   => "{$this->collectionNamespace}/{$this->formClass}Controller",
            '--form' => true,
        ]);
    }

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getStub()
    {
        return lit_vendor_path('stubs/form.config.stub');
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return $this->formClass;
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
            'DummyControllerRouteName' => Str::slug($this->collection),
            'DummyFormRouteName'       => Str::slug($this->formName),
            'DummyController'          => $this->formClass.'Controller',
            'DummyCollection'          => $this->collectionNamespace,
            'DummyFormName'            => $this->formClass,
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace."\\Config\\Form\\{$this->collectionNamespace}";
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        if (! Str::endsWith($name, 'Config')) {
            $name .= 'Config';
        }

        return parent::qualifyClass($name);
    }

    /**
     * Set collection and form name.
     *
     * @return void
     */
    protected function setCollectionAndFormName()
    {
        $collection = $this->argument('collection') ?? $this->option('collection');
        if (! $collection) {
            $collection = $this->ask('enter the collection name (snake_case, plural)');
        }
        $formName = $this->argument('name') ?? $this->option('form');
        if (! $formName) {
            $formName = $this->ask('enter the form name (snake_case)');
        }

        $this->collection = Str::snake($collection);
        $this->collectionNamespace = ucfirst(Str::camel($this->collection));
        $this->formName = Str::snake($formName);
        $this->formClass = ucfirst(Str::camel($this->formName));
    }
}
