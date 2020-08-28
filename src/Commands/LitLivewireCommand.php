<?php

namespace Ignite\Commands;

use Illuminate\Console\Command;

class LitLivewireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:livewire {name} {--force} {--inline}';

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
     * Create new LitLivewireCommand instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

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
        config()->set('livewire.class_namespace', 'Http\\Livewire');
        config()->set('livewire.view_path', base_path('lit/resources/views/livewire'));
        set_unaccessible_property(app(), 'namespace', 'Lit');
        app()->instance('path', base_path('lit/app'));
        app()->setBasePath(base_path('lit'));

        $this->call('make:livewire', [
            'name'     => $this->argument('name'),
            '--force'  => $this->option('force'),
            '--inline' => $this->option('inline'),
        ]);

        app()->instance('path', $this->original['app_path']);
        app()->setBasePath($this->original['base_path']);
        set_unaccessible_property(app(), 'namespace', $this->original['app_namespace']);
        config()->set('livewire.class_namespace', $this->original['class_namespace']);
        config()->set('livewire.view_path', $this->original['view_path']);
    }
}
