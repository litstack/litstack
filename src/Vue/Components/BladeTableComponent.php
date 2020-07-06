<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\TableComponent;
use Illuminate\Contracts\View\View;

class BladeTableComponent extends TableComponent
{
    /**
     * Get array.
     *
     * @return array
     */
    public function render(): array
    {
        if ($this->props['view'] instanceof View) {
            $this->props['view'] = $this->props['view']->render();
        }

        return parent::render();
    }
}
