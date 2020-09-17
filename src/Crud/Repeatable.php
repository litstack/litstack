<?php

namespace Ignite\Crud;

use Ignite\Crud\Fields\Block\RepeatableForm;
use Ignite\Page\Table\ColumnBuilder;

abstract class Repeatable
{
    /**
     * Build repeatable preview.
     *
     * @param  ColumnBuilder $preview
     * @return void
     */
    abstract public function preview(ColumnBuilder $preview);

    /**
     * Build repeatable form.
     *
     * @param  RepeatableForm $form
     * @return void
     */
    abstract public function form(RepeatableForm $form);
}
