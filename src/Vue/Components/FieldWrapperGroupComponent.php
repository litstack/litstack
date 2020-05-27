<?php

namespace Fjord\Vue\Components;

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
            'cols' => [
                'type' => 'integer',
                'required' => false,
                'default' => 12
            ],
            'class' => [
                'type' => 'string',
                'required' => false,
                'default' => 'mb-4'
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
}
