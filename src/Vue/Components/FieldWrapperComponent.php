<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;

class FieldWrapperComponent extends Component
{
    /**
     * Before mount lifecycle hook.
     *
     * @return void
     */
    public function beforeMount()
    {
        $this->props['children'] = collect([]);
    }

    /**
     * Set wrapper component.
     *
     * @param  Component $component
     * @return $thi
     */
    public function wrapperComponent(Component $component)
    {
        return $this->prop('wrapperComponent', $component);
    }

    /**
     * Add child component.
     *
     * @param  mixed $name
     * @return void
     */
    public function component($component)
    {
        $component = component($component);

        $this->props['children'][] = $component;

        return $component;
    }
}
