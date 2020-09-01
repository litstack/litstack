<?php

namespace Ignite\Application\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\Filesystem as FilesystemFilesystem;
use Illuminate\Support\Facades\File;
use Livewire\Commands\ComponentParser;

class LivewireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:livewire {name} 
                            {--force : Wether to overwrite the existing component and view} 
                            {--inline : Wether to only create the component file}';

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
     * Wether the livewire component file already exists.
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
    public function __construct(FilesystemFilesystem $files)
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

        $this->call('make:livewire', [
            'name'     => $this->argument('name'),
            '--force'  => $force = $this->option('force'),
            '--inline' => $inline = $this->option('inline'),
        ]);

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
        dd(
            $this->parser->baseViewPath,
            resource_path('views')
        );

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
        config()->set('livewire.view_path', base_path('lit/resources/views/livewire'));
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
