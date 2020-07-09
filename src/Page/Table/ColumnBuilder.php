<?php

namespace Fjord\Page\Table;

use Fjord\Support\VueProp;
use Fjord\Vue\Components\BladeTableComponent;
use Fjord\Vue\TableComponent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFactory;

class ColumnBuilder extends VueProp
{
    /**
     * Table column stack.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * Add column.
     *
     * @param  string $label
     * @return Column
     */
    public function col($label = '')
    {
        return $this->columns[] = new Column($label);
    }

    /**
     * Add table column to cols stack and set component.
     *
     * @param  string $component
     * @return mixed
     */
    public function component($component)
    {
        return $this->columns[] = component($component, TableComponent::class);
    }

    /**
     * Add Blade View column.
     *
     * @param  View|string $view
     * @return View
     */
    public function view($view)
    {
        if (! $view instanceof View) {
            $view = ViewFactory::make($view);
        }

        $this->component(new BladeTableComponent('fj-blade'))->prop('view', $view);

        return $view;
    }

    /**
     * Add toggle column.
     *
     * @param  string    $key
     * @return Component
     */
    public function toggle($key)
    {
        return $this->component('fj-col-toggle')
            ->prop('link', false)
            ->prop('local_key', $key);
    }

    /**
     * Add image column.
     *
     * @param  string            $label
     * @return ColImageComponent
     */
    public function image($label = '')
    {
        return $this->component('fj-col-image')->label($label);
    }

    /**
     * Add relation column.
     *
     * @param  string         $label
     * @return TableComponent
     */
    public function relation($label = '')
    {
        return $this->component('fj-col-crud-relation')->prop('label', $label);
    }

    /**
     * Render Builder.
     *
     * @return array
     */
    public function render(): array
    {
        return $this->columns;
    }
}
