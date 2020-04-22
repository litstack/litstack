<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;

class InfoComponent extends Component
{
    /**
     * Available component properties.
     *
     * @var array
     */
    protected $available = [
        'title',
        'text',
        'cols',
        'heading',
        'class'
    ];

    /**
     * Required component properties.
     *
     * @var array
     */
    protected $required = [
        'title',
        'text'
    ];

    /**
     * Default component properties.
     *
     * @var array
     */
    protected $defaults = [
        'text' => [],
        'cols' => 4,
        'heading' => 'h4'
    ];

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
