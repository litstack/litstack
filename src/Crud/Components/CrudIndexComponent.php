<?php

namespace Fjord\Crud\Components;

use Closure;
use Fjord\Vue\Component;
use Illuminate\Support\Str;

class CrudIndexComponent extends Component
{
    // Add methods that should be used in extensions.

    /**
     * Add to recordActions prop.
     *
     * @param string $component
     * @return void
     */
    public function addRecordAction(string $component)
    {
        $this->props['recordActions'][] = $component;
    }

    /**
     * Add to headerComponent prop.
     *
     * @param string $component
     * @return void
     */
    public function headerComponent(string $component)
    {
        $this->props['headerComponents'][] = $component;
    }

    /**
     * Edit index.
     *
     * @param Closure $closure
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
     * @return boolean
     */
    public function is(string $model)
    {
        return $this->props['formConfig']['model'] == $model;
    }

    /**
     * Should extension be executed.
     *
     * @param string $name
     * @return boolean
     */
    public function resolveExtension(string $name = ''): bool
    {
        return Str::endsWith($this->props['config']['route_prefix'], $name);
    }
}
