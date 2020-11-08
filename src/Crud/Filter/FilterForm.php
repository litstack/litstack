<?php

namespace Ignite\Crud\Filter;

use Ignite\Crud\BaseForm;

class FilterForm extends BaseForm
{
    /**
     * Create new BaseForm instance.
     *
     * @param  string $filter
     * @param  string $model
     * @return void
     */
    public function __construct(string $filter, string $model = '')
    {
        parent::__construct($model);

        $this->setRoutePrefix('');

        $this->registered(function ($field) use ($filter) {
            $field->setAttribute('filter', $filter);
        });

        (new $filter)->form($this);
    }
}
