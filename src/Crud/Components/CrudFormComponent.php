<?php

namespace Fjord\Crud\Components;

use Fjord\Vue\RootComponent;

class CrudFormComponent extends RootComponent
{
    /**
     * Available slots.
     *
     * @return array
     */
    protected function slots()
    {
        return array_merge(parent::slots(), [
            //
        ]);
    }

    /**
     * Check for crud model.
     *
     * @param string $model
     * @return boolean
     */
    public function isModel(string $model)
    {
        return $this->props['config']['model'] == $model;
    }

    /**
     * Should extension be executed.
     *
     * @param string $name
     * @return boolean
     */
    public function resolveExtension(string $name = ''): bool
    {
        $currentName = $this->props['formConfig']['names']['table'];

        return $name == $currentName;
    }
}
