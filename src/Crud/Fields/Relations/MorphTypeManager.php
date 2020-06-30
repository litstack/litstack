<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\BaseForm;

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

    public function __construct(string $id, BaseForm $form, $selectId)
    {
        $this->id = $id;
        $this->form = $form;
        $this->selectId = $selectId;
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
            ->dependsOn($this->selectId, $model);

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
