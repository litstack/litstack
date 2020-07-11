<?php

namespace Fjord\Page\Table\Components;

use Illuminate\Contracts\View\View;

class BladeColumnComponent extends ColumnComponent
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
