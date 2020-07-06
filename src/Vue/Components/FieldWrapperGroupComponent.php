<?php

namespace Fjord\Vue\Components;

use Fjord\Crud\FieldDependency;
use Fjord\Vue\Component;

class FieldWrapperGroupComponent extends Component
{
    /**
     * Available props.
     *
     * @return array
     */
    protected function props()
    {
        return [
            'width' => [
                'type'     => 'integer',
                'required' => false,
                'default'  => 12,
            ],
            'class' => [
                'type'     => 'string',
                'required' => false,
                'default'  => 'mb-4',
            ],
            'dependencies' => [
                'default' => collect([]),
            ],
        ];
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

        return parent::__call($method, $parameters);
    }
}
