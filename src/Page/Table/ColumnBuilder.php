<?php

namespace Fjord\Page\Table;

use Fjord\Contracts\Page\Column as ColumnContract;
use Fjord\Contracts\Page\ColumnBuilder as ColumnBuilderContract;
use Fjord\Page\Table\Components\BladeColumnComponent;
use Fjord\Page\Table\Components\ColumnComponent;
use Fjord\Page\Table\Components\ImageComponent;
use Fjord\Page\Table\Components\RelationComponent;
use Fjord\Page\Table\Components\ToggleComponent;
use Fjord\Support\VueProp;
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
    public function col($label = ''): ColumnContract
    {
        return $this->columns[] = new Column($label);
    }

    /**
     * Add table column to cols stack and set component.
     *
     * @param  string          $component
     * @return ColumnComponent
     */
    public function component($component): ColumnContract
    {
        return $this->columns[] = component($component, ColumnComponent::class);
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

        $this->component(new BladeColumnComponent('fj-blade'))->prop('view', $view);

        return $view;
    }

    /**
     * Add toggle column.
     *
     * @param  string          $key
     * @return ToggleComponent
     */
    public function toggle($attribute)
    {
        return $this->component(new ToggleComponent('fj-col-toggle'))
            ->prop('link', false)
            ->prop('local_key', $attribute);
    }

    /**
     * Add image column.
     *
     * @param  string         $label
     * @return ImageComponent
     */
    public function image($label = '')
    {
        return $this->component(new ImageComponent('fj-col-image'))->label($label);
    }

    /**
     * Add relation column.
     *
     * @param  string            $label
     * @return RelationComponent
     */
    public function relation($label = '')
    {
        return $this->component(new RelationComponent('fj-col-crud-relation'))->prop('label', $label);
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
