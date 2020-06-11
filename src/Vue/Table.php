<?php

namespace Fjord\Vue;

use Closure;
use Fjord\Support\VueProp;
use Fjord\Vue\Traits\Authorizable;
use Fjord\Vue\Contracts\AuthorizableContract;

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
     * @return ColImageComponent
     */
    public function toggle(string $key)
    {
        $component = $this->component('fj-col-toggle');

        $component->link(false);
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

        $component->label($label);

        return $component;
    }

    /**
     * Add table column to cols stack and set component.
     *
     * @param string $component
     * @return mixed
     */
    public function component(string $component)
    {
        $component = component($component, TableComponent::class);

        $this->cols[] = $component;

        return $component;
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
