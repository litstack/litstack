<?php

namespace Lit\Page\Table\Components;

use Lit\Vue\Traits\StaticComponentName;
use Illuminate\Contracts\View\View;

class BladeColumnComponent extends ColumnComponent
{
    use StaticComponentName;

    /**
     * Component name.
     *
     * @var string
     */
    protected $name = 'fj-blade';

    /**
     * Set blade view.
     *
     * @param  View  $view
     * @return $this
     */
    public function view(View $view)
    {
        return $this->prop('view', $view);
    }

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
