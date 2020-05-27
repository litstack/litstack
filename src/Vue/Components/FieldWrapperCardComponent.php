<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;

class FieldWrapperCardComponent extends Component
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
            'width' => [
                'type' => ['integer', 'double'],
                'required' => false,
                'default' => 12
            ],
            'class' => [
                'type' => 'string',
                'required' => false,
                'default' => 'mb-4'
            ],
        ];
    }
}
