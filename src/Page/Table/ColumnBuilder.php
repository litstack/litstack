<?php

namespace Lit\Page\Table;

use Lit\Contracts\Page\Column as ColumnContract;
use Lit\Contracts\Page\ColumnBuilder as ColumnBuilderContract;
use Lit\Crud\Fields\Relations\LaravelRelationField;
use Lit\Page\Table\Casts\MoneyColumn;
use Lit\Page\Table\Components\BladeColumnComponent;
use Lit\Page\Table\Components\ColumnComponent;
use Lit\Page\Table\Components\ImageComponent;
use Lit\Page\Table\Components\RelationComponent;
use Lit\Page\Table\Components\ToggleComponent;
use Lit\Support\VueProp;
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
     * Parent instance.
     *
     * @var Table|LaravelRelationField|null
     */
    protected $parent;

    /**
     * Set table instance.
     *
     * @param  Table|LaravelRelationField $parent
     * @return void
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Gets parent.
     *
     * @return Table|LaravelRelationField|null
     */
    public function getParent()
    {
        return $this->parent;
    }

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
     * Create new Money column.
     *
     * @param  string $column
     * @param  string $currency
     * @return Column
     */
    public function money($column, $currency = 'EUR', $locale = null)
    {
        if ($this->parent) {
            $this->parent->cast(
                $column,
                MoneyColumn::class.":{$currency},{$locale}"
            );
        }

        return $this->col(ucfirst($column))
            ->class('lit-col-money')
            ->value("{{$column}}")
            ->sortBy($column)
            ->right();
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

        $this->component(new BladeColumnComponent('lit-blade'))->prop('view', $view);

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
        return $this->component(new ToggleComponent('lit-col-toggle'))
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
        return $this->component(new ImageComponent('lit-col-image'))->label($label);
    }

    /**
     * Add avatar image column.
     *
     * @param  string         $label
     * @return ImageComponent
     */
    public function avatar($label = '')
    {
        return $this->image($label)->circle();
    }

    /**
     * Add relation column.
     *
     * @param  string            $label
     * @return RelationComponent
     */
    public function relation($label = '')
    {
        return $this->component(new RelationComponent('lit-col-crud-relation'))->prop('label', $label);
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
