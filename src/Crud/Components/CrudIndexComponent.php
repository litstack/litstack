<?php

namespace Fjord\Crud\Components;

use Closure;
use Fjord\Vue\RootComponent;
use Illuminate\Support\Str;

class CrudIndexComponent extends RootComponent
{
    /**
     * Available slots.
     *
     * @return array
     */
    public function slots()
    {
        return array_merge(parent::slots(), [
            'indexControls' => [
                'many' => true,
            ],
            'index' => [
                'many' => true,
            ],
        ]);
    }

    /**
     * Edit index.
     *
     * @param Closure $closure
     *
     * @return void
     */
    public function index(Closure $closure)
    {
        $closure($this->props['config']['index']);
    }

    /**
     * Check for crud model.
     *
     * @param string $model
     *
     * @return bool
     */
    public function isModel(string $model)
    {
        return $this->props['config']['model'] == $model;
    }

    /**
     * Should extension be executed.
     *
     * @param string $name
     *
     * @return bool
     */
    public function resolveExtension(string $name = ''): bool
    {
        return Str::endsWith($this->props['config']['route_prefix'], $name);
    }
}
