<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;

class FjCardComponent extends Component
{
    /**
     * Available component properties.
     *
     * @var array
     */
    protected $available = [
        'title',
        'cols',
        'component',
        'class'
    ];

    /**
     * Required component properties.
     *
     * @var array
     */
    protected $required = [
        'title',
        'component'
    ];

    /**
     * Default component properties.
     *
     * @var array
     */
    protected $defaults = [
        'cols' => 12,
        'class' => 'mb-4'
    ];

    /**
     * Set component.
     *
     * @param string $name
     * @return void
     */
    public function component(string $name)
    {
        $component = component($name);

        $this->props['component'] = $component;

        return $component;
    }
}
