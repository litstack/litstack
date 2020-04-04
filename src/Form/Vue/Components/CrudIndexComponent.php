<?php

namespace AwStudio\Fjord\Form\Vue\Components;

use AwStudio\Fjord\Application\Vue\Component;

class CrudIndexComponent extends Component
{
    // Add methods that should be used in extensions.

    /**
     * Add to recordActions prop.
     *
     * @param string $component
     * @return void
     */
    public function addRecordAction(string $component)
    {
        $this->props['recordActions'][] = $component;
    }

    /**
     * Add to globalActions prop.
     *
     * @param string $component
     * @return void
     */
    public function addGlobalAction(string $component)
    {
        $this->props['globalActions'][] = $component;
    }

    /**
     * Check for crud model.
     *
     * @param string $model
     * @return boolean
     */
    public function is(string $model)
    {
        return $this->props['formConfig']['model'] == $model;
    }

    /**
     * Should extension be executed.
     *
     * @param string $name
     * @return boolean
     */
    public function executeExtension(string $name = ''): bool
    {
        $currentName = $this->props['formConfig']['names']['table'];

        return $name == $currentName;
    }
}
