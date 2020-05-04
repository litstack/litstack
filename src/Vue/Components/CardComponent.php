<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;

class CardComponent extends Component
{
    /**
     * Available props.
     *
     * @return array
     */
    protected function props()
    {
        return [
            'title' => [
                'type' => 'string',
                'required' => false,
            ],
            'cols' => [
                'type' => 'integer',
                'required' => false,
                'default' => 12
            ],
            'component' => [
                'type' => ['string', 'object'],
                'required' => true,
                'default' => function () {
                    return collect([]);
                }
            ],
            'class' => [
                'type' => 'string',
                'required' => false,
                'default' => 'mb-4'
            ],
        ];
    }

    /**
     * Set component.
     *
     * @param string $name
     * @return void
     */
    public function component(string $name)
    {
        $component = component($name);

        $this->props['component'][] = $component;

        return $component;
    }
}
