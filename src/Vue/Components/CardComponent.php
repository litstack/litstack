<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;

class CardComponent extends Component
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
        'component'
    ];

    /**
     * Default component properties.
     *
     * @var array
     */
    protected $defaults = [
        'cols' => 12,
        'class' => 'mb-4',
        'component' => []
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

        $this->props['component'][] = $component;

        return $component;
    }


    /**
     * Set defaults.
     *
     * @return void
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $this->props['component'] = collect([]);
    }
}
