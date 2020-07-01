<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;

class FieldWrapperComponent extends Component
{
    /**
     * Available props.
     *
     * @return array
     */
    protected function props()
    {
        return [
            'wrapperComponent' => [
                'type'     => Component::class,
                'required' => true,
            ],
            'children' => [
                'type'     => ['array', 'object'],
                'required' => true,
                'default'  => function () {
                    return collect([]);
                },
            ],
        ];
    }

    /**
     * Add child component.
     *
     * @param mixed $name
     *
     * @return void
     */
    public function component($component)
    {
        if (is_string($component)) {
            $component = component($component);
        }

        $this->props['children'][] = $component;

        return $component;
    }
}
