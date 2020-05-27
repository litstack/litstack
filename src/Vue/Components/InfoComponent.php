<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;
use Fjord\Exceptions\InvalidArgumentException;
use Fjord\Exceptions\MethodNotAllowedException;

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
            'width' => [
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
     * Add text.
     *
     * @param string $name
     * @return self
     */
    public function text(string $text)
    {
        $this->props['text'][] = $text;

        return $this;
    }
}
