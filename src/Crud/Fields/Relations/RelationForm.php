<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\BaseForm;

class RelationForm extends BaseForm
{
    /**
     * Relation field.
     *
     * @var LaravelRelationField
     */
    protected $field;

    /**
     * Create new RelationForm instance.
     *
     * @param  string               $model
     * @param  LaravelRelationField $field
     * @return void
     */
    public function __construct($model, LaravelRelationField $field)
    {
        parent::__construct($model);
        $this->field = $field;
    }

    /**
     * Get relation field.
     *
     * @return LaravelRelationField
     */
    public function getParentField()
    {
        return $this->field;
    }

    /**
     * Get pivot field.
     *
     * @return void
     */
    public function pivot()
    {
        return new PivotField($this);
    }
}
