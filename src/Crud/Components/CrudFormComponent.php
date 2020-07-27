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
     *
     * @return bool
     */
    public function isModel(string $model)
    {
        return $this->props['config']['model'] == $model;
    }
}
