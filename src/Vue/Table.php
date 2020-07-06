<?php

namespace Fjord\Vue;

use Fjord\Contracts\Vue\Authorizable as AuthorizableContract;
use Fjord\Support\VueProp;
use Fjord\Vue\Components\BladeTableComponent;
use Fjord\Vue\Traits\Authorizable;
use Illuminate\Contracts\View\View;

class Table extends VueProp implements AuthorizableContract
{
    use Authorizable;

    /**
     * Column stack.
     *
     * @var array
     */
    protected $cols = [];

    /**
     * Add table column to cols stack.
     *
     * @param string $label
     *
     * @return \Fjord\Vue\Col $col
     */
    public function col(string $label = '')
    {
        $col = new Col($label);

        $this->cols[] = $col;

        return $col;
    }

    /**
     * Add toggle column.
     *
     * @param string $key
     *
     * @return ColImageComponent
     */
    public function toggle(string $key)
    {
        $component = $this->component('fj-col-toggle');

        $component->prop('link', false);
        $component->prop('local_key', $key);

        return $component;
    }

    /**
     * Add image column.
     *
     * @return ColImageComponent
     */
    public function image(string $label = '')
    {
        $component = $this->component('fj-col-image');

        $component->label($label);

        return $component;
    }

    /**
     * Add relation column.
     *
     * @return ColCrudRelationComponent
     */
    public function relation(string $label = '')
    {
        $component = $this->component('fj-col-crud-relation');

        $component->prop('label', $label);

        return $component;
    }

    /**
     * Add table column to cols stack and set component.
     *
     * @param string $component
     *
     * @return mixed
     */
    public function component($component)
    {
        $component = component($component, TableComponent::class);

        $this->cols[] = $component;

        return $component;
    }

    public function view($view)
    {
        if (! $view instanceof View) {
            $view = view($view);
        }

        return $this->component(new BladeTableComponent('fj-blade'))->prop('view', $view);
    }

    /**
     * Get cols.
     *
     * @return array $cols
     */
    public function render(): array
    {
        return $this->cols;
    }
}
