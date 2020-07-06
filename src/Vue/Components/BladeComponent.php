<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;
use Illuminate\Contracts\View\View;

class BladeComponent extends Component
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
