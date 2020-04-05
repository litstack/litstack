<?php

namespace Fjord\Form\Vue\Components;

use Fjord\Application\Vue\Component;

class CrudShowComponent extends Component
{
    // Add methods that should be used in extensions.

    /**
     * Add to headerComponents prop.
     *
     * @param string $component
     * @return void
     */
    public function addHeaderComponent(string $component)
    {
        $this->props['headerComponents'][] = $component;
    }

    /**
     * Add to controls prop.
     *
     * @param string $component
     * @return void
     */
    public function addControls(string $component)
    {
        $this->props['controls'][] = $component;
    }

    /**
     * Add to content prop.
     *
     * @param string $component
     * @return void
     */
    public function appendContent(string $component)
    {
        $this->props['content'][] = $component;
    }

    /**
     * Prepend to content prop.
     *
     * @param string $component
     * @return void
     */
    public function prependContent(string $component)
    {
        array_unshift($this->props['content'], $component);
    }

    /**
     * Should extension be executed.
     *
     * @param string $name
     * @return boolean
     */
    public function executeExtension(string $name = ''): bool
    {
        return true;
        $currentName = $this->props['formConfig']['names']['table'];

        return $name == $currentName;
    }
}
