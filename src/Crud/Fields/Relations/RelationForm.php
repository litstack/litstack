<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\BaseForm;

class RelationForm extends BaseForm
{
    public function pivot()
    {
        return new RelationPivotForm($this);
    }
}
