<?php

namespace Ignite\Contracts\Crud;

use Closure;
use Ignite\Crud\BaseForm;

interface Formable
{
    /**
     * Add a form.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function form(Closure $closure);

    /**
     * Get the added form.
     *
     * @return BaseForm|mixed|null
     */
    public function getForm();
}
