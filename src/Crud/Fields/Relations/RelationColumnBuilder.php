<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\Field;
use Ignite\Crud\Traits\ColumnBuilderHasFields;
use Ignite\Page\Table\ColumnBuilder;

class RelationColumnBuilder extends ColumnBuilder
{
    use ColumnBuilderHasFields;

    /**
     * Field column component name.
     *
     * @var string
     */
    protected $fieldColumnComponentName = 'lit-field-relation-col-field';

    /**
     * LaravelRelationField instance.
     *
     * @var LaravelRelationField
     */
    protected $field;

    /**
     * Create new RelationColumnBuilder instance.
     *
     * @param  LaravelRelationField $field
     * @return $this
     */
    public function __construct(LaravelRelationField $field)
    {
        $this->field = $field;
    }

    /**
     * Get the model class belonging to the field.
     *
     * @return string
     */
    protected function getFieldModel()
    {
        return $this->field->getRelatedModelClass();
    }

    /**
     * Get field route prefix.
     *
     * @return string
     */
    protected function getFieldRoutePrefix()
    {
        return $this->field->route_prefix;
    }

    /**
     * Get field params.
     *
     * @param  string $formKey
     * @param  Field  $field
     * @return array
     */
    protected function getFieldParams($formKey, Field $field): array
    {
        return [
            'child_field_id' => $field->id,
            'field_id'       => $this->field->id,
            'form_key'       => $formKey,
        ];
    }
}
