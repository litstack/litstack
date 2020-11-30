<?php

namespace Ignite\Application\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Livewire\Commands\ComponentParser;

class LivewireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:livewire {name} 
                            {--force : whether to overwrite the existing component and view} 
                            {--inline : whether to only create the component file}';

    /**
     * Command description.
     *
     * @var string
     */
    protected $description = 'Create a new Livewire component to the litstack app.';

    /**
     * Original values.
     *
     * @var string
     */
    protected $originals = [];

    /**
     * Component parser instance.
     *
     * @var ComponentParser
     */
    protected $parser;

    /**
     * Filessystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * whether the livewire component file already exists.
     *
     * @var bool
     */
    protected $exists = false;

    /**
     * Create new LitLivewireCommand instance.
     *
     * @param  Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;

        $this->original['class_namespace'] = config('livewire.class_namespace');
        $this->original['view_path'] = config('livewire.view_path');
        $this->original['app_namespace'] = app()->getNamespace();
        $this->original['app_path'] = app('path');
        $this->original['base_path'] = base_path();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->mockEnvironment();

        $this->parser = new ComponentParser(
            'Lit\\Http\\Livewire',
            config('livewire.view_path'),
            $this->argument('name')
        );

        $this->exists = $this->files->exists($this->parser->classPath());

        $arguments = [
            'name'    => $this->argument('name'),
            '--force' => $force = $this->option('force'),
        ];

        if ($inline = $this->option('inline')) {
            $arguments['--inline'] = $inline;
        }

        $this->call('make:livewire', $arguments);

        $this->fixViewNamespace($force, $inline);

        $this->resetEnvironment();
    }

    /**
     * Fix view namespace.
     *
     * @param  bool $force
     * @param  bool $inline
     * @return void
     */
    protected function fixViewNamespace(bool $force = false, bool $inline = false)
    {
        if ($inline) {
            return;
        }

        if ($this->exists && ! $force) {
            return;
        }

        $content = str_replace(
            $this->parser->viewName(),
            'lit::'.$this->parser->viewName(),
            $this->files->get($this->parser->classPath())
        );

        $this->files->put($this->parser->classPath(), $content);
    }

    /**
     * Mock environment to make livewire place files in the desired places.
     *
     * @return void
     */
    protected function mockEnvironment()
    {
        config()->set('livewire.class_namespace', 'Lit\\Http\\Livewire');
        config()->set(
            'livewire.view_path',
            base_path('lit'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views/livewire')
        );
        set_unaccessible_property(app(), 'namespace', 'Lit\\');
        app()->instance('path', base_path('lit/app'));
        app()->setBasePath(base_path('lit'));
    }

    /**
     * Reset environment.
     *
     * @return void
     */
    protected function resetEnvironment()
    {
        app()->instance('path', $this->original['app_path']);
        app()->setBasePath($this->original['base_path']);
        set_unaccessible_property(app(), 'namespace', $this->original['app_namespace']);
        config()->set('livewire.class_namespace', $this->original['class_namespace']);
        config()->set('livewire.view_path', $this->original['view_path']);
    }
}
