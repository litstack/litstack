<?php

namespace Fjord\Vue\Traits;

use Exception;
use Fjord\Vue\Component;

trait HasComponent
{
    /**
     * Set Vue component name.
     *
     * @param string $component
     * @return self
     */
    public function component(string $component)
    {
        $component = new Component($component);

        $this->attributes['component'] = $component;

        return $component;
    }

    /**
     * Set component props.
     *
     * @param array $props
     * @return self
     */
    public function props(array $props)
    {
        if (!array_key_exists('component', $this->attributes)) {
            throw new Exception('Props can only be passed to Vue component table columns. You many call "component" before "props".');
        }

        $this->attributes['props'] = $props;

        return $this;
    }
}
