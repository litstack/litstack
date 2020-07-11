<?php

namespace Fjord\Page\Table;

use Fjord\Contracts\Page\Column;
use Fjord\Contracts\Page\ColumnBuilder as ColumnBuilderContract;
use Fjord\Support\VueProp;
use Fjord\Vue\Components\BladeTableComponent;
use Fjord\Vue\TableComponent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFactory;

class ColumnBuilder extends VueProp implements ColumnBuilderContract
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
    public function col($label = ''): Column
    {
        return $this->columns[] = new Column($label);
    }

    /**
     * Add table column to cols stack and set component.
     *
     * @param  string $component
     * @return mixed
     */
    public function component($component): Column
    {
        return $this->columns[] = component($component, TableComponent::class);
    }

    /**
     * Add Blade View column.
     *
     * @param  View|string $view
     * @return View
     */
    public function view($view): View
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
    public function toggle($attribute)
    {
        return $this->component('fj-col-toggle')
            ->prop('link', false)
            ->prop('local_key', $attribute);
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
