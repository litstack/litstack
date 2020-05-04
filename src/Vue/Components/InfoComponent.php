<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;

class InfoComponent extends Component
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
            'text' => [
                'type' => 'array',
                'required' => true,
                'default' => []
            ],
            'cols' => [
                'type' => 'integer',
                'required' => false,
                'default' => 4
            ],
            'heading' => [
                'type' => 'string',
                'required' => true,
                'default' => 'h4'
            ],
        ];
    }

    /**
     * Set component.
     *
     * @param string $name
     * @return void
     */
    public function text(string $text)
    {
        $this->props['text'][] = $text;

        return $this;
    }
}
