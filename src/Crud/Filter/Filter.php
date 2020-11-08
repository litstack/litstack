<?php

namespace Ignite\Crud\Filter;

abstract class Filter
{
    /**
     * Creates filter form.
     *
     * @param  FilterForm $form
     * @return void
     */
    abstract public function form(FilterForm $form);
}
