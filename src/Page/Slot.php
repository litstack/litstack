<?php

namespace Fjord\Page;

use Fjord\Support\VueProp;

class Slot extends VueProp
{
    /**
     * Component stack.
     *
     * @var array
     */
    protected $components = [];

    /**
     * Add component to Slot.
     *
     * @return $this
     */
    public function component($component)
    {
        $component = component($component);

        $this->components[] = $component;

        return $component;
    }

    /**
     * Render slot for Vue.
     *
     * @return array
     */
    public function render(): array
    {
        return [
            'components' => collect($this->components),
        ];
    }
}
