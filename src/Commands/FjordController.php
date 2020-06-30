<?php

namespace Fjord\Commands;

use Illuminate\Support\Str;

class FjordController extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:controller {name}
                            {--form : Whether to create a form controller chart }
                            {--crud : Whether to create a crud controller }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will create a controller to the FjordApp namespace.';

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return bool|null
     */
    public function handle()
    {
        if ($this->setControllerType() === false) {
            $this->error('Only one controller type can be selected');

            return false;
        }

        parent::handle();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return [
            'default' => fjord_path('stubs/controller.default.stub'),
            'form'    => fjord_path('stubs/controller.form.stub'),
            'crud'    => fjord_path('stubs/controller.crud.stub'),
        ][$this->type];
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [
            'default' => fn () => [],
            'form'    => fn ()    => $this->buildCrudReplacements($name),
            'crud'    => fn ()    => $this->buildCrudReplacements($name),
        ][$this->type]();

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Build crud controller replacements.
     *
     * @param string $name
     *
     * @return array
     */
    protected function buildCrudReplacements(string $name)
    {
        $modelClassName = str_replace('Controller', '', last(split_path($name)));
        $tableName = strtolower(Str::plural($modelClassName));

        return [
            'DummyModelClass' => $modelClassName,
            'DummyTableName'  => $tableName,
        ];
    }

    /**
     * Set chart type from options.
     *
     * @return bool|null
     */
    public function setControllerType()
    {
        $this->type = null;
        foreach ([
            'form', 'crud',
        ] as $type) {
            if (!$this->option($type)) {
                continue;
            }

            // Returning false when type has already been set since multiple
            // types are not allowed.
            if ($this->type) {
                return false;
            }

            $this->type = $type;
        }

        if (!$this->type) {
            $this->type = 'default';
        }
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return [
            'default' => $rootNamespace."\Controllers",
            'form'    => $rootNamespace."\Controllers\Form",
            'crud'    => $rootNamespace."\Controllers\Crud",
        ][$this->type];
    }
}
