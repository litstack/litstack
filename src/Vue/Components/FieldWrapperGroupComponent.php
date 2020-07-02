<?php

namespace Fjord\Vue\Components;

use Fjord\Crud\FieldDependency;
use Fjord\Vue\Component;
use InvalidArgumentException;

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
            'dependsOn' => [
                'type' => 'object',

            ],
        ];
    }

    public function dependsOn(string $key, $value)
    {
        $this->props['dependsOn'] = ['key' => $key, 'value' => $value];

        return $this;
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
        try {
            return $this->addDependency(
                FieldDependency::make($method, ...$parameters)
            );
        } catch (InvalidArgumentException $e) {
        }

        return parent::__call($method, $parameters);
    }
}
