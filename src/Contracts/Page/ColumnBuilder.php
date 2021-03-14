<?php

namespace Ignite\Contracts\Page;

use Ignite\Page\Table\Components\ImageComponent;
use Illuminate\Contracts\View\View;

interface ColumnBuilder
{
    /**
     * Registers column.
     *
     * @param  string $label
     * @return Column
     */
    public function col($label = ''): Column;

    /**
     * Registers Vue component column.
     *
     * @param  string $component
     * @return Column
     */
    public function component($component): Column;

    /**
     * Registers Blade component column.
     *
     * @param  View|string $view
     * @return Column
     */
    public function view($view): Column;

    /**
     * Add livewire component column.
     *
     * @param  string $component
     * @param  array  $data
     * @return View
     */
    public function livewire($component, $data = []);

    /**
     * Registers image column.
     *
     * @param  string         $label
     * @return ImageComponent
     */
    public function image($label = '');

    /**
     * Add avatar image column.
     *
     * @param  string         $label
     * @return ImageComponent
     */
    public function avatar($label = '');

    /**
     * Create new Money column.
     *
     * @param  string $column
     * @param  string $currency
     * @return Column
     */
    public function money($column, $currency = 'EUR', $locale = null);

    /**
     * Registers relation column.
     *
     * @param  string $label
     * @return mixed
     */
    public function relation($label = '');

    /**
     * Registers toggle column.
     *
     * @param  string $attribute
     * @return Column
     */
    public function toggle($attribute);
}
