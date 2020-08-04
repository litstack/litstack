<?php

namespace Fjord\Contracts\Page;

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
     * @return View
     */
    public function view($view): View;

    /**
     * Registers image column.
     *
     * @param  string $label
     * @return mixed
     */
    public function image($label = '');

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
