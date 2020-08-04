<?php

namespace Fjord\Vue\Components;

use Fjord\Contracts\Vue\Resizable;
use Fjord\Crud\FieldDependency;
use Fjord\Vue\Component;
use Fjord\Vue\Traits\CanBeResized;

class FieldWrapperGroupComponent extends Component implements Resizable
{
    use CanBeResized;

    /**
     * Before mount lifecycle hook.
     *
     * @return void
     */
    public function beforeMount()
    {
        $this->props['dependencies'][] = collect([]);
        $this->class('mb-4');
        $this->width(12);
    }

    /**
     * Add dependency.
     *
     * @param  FieldDependency $dependency
     * @return $this
     */
    public function addDependency(FieldDependency $dependency)
    {
        $this->props['dependencies'][] = $dependency;

        return $this;
    }

    /**
     * Call component method.
     *
     * @param  string $method
     * @param  array  $params
     * @return mixed
     */
    public function __call($method, $parameters = [])
    {
        if (FieldDependency::conditionExists($method)) {
            return $this->addDependency(
                FieldDependency::make($method, ...$parameters)
            );
        }
    }
}
