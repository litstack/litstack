<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\BaseForm;
use Ignite\Crud\Field;

class MorphTypeManager
{
    /**
     * Morph types.
     *
     * @var array
     */
    protected $types = [];

    protected $id;

    protected $form;

    public function __construct(string $id, BaseForm $form, Field $selectField)
    {
        $this->id = $id;
        $this->form = $form;
        $this->selectField = $selectField;
    }

    /**
     * Add morph type.
     *
     * @param string $model
     *
     * @return void
     */
    public function to(string $model)
    {
        $idDivider = MorphTo::ID_DIVIDER;
        $morphId = "{$this->id}{$idDivider}{$model}";
        $field = $this->form
            ->registerField(MorphTo::class, $morphId)
            ->when($this->selectField, $model);

        $this->types[$model] = $field;

        return $field;
    }

    public function registerMorphType()
    {
        // code...
    }

    /**
     * Get types.
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
}
