<?php

namespace Fjord\Contracts\Page;

use Fjord\Page\Table\Components\ImageComponent;
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
