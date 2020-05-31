<?php

namespace Fjord\Crud\Fields\Relations;

class OneRelationField extends LaravelRelationField
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-relation';

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        parent::setDefaultAttributes();

        $this->setAttribute('many', false);
    }
}
