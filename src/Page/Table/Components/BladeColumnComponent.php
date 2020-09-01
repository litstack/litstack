<?php

namespace Ignite\Page\Table\Components;

use Ignite\Vue\Traits\StaticComponentName;
use Illuminate\Contracts\View\View;

class BladeColumnComponent extends ColumnComponent
{
    use StaticComponentName;

    /**
     * Component name.
     *
     * @var string
     */
    protected $name = 'lit-blade';

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
