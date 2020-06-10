<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\BaseForm;
use Fjord\Support\Facades\Crud;

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

    public function __construct(string $id, BaseForm $form)
    {
        $this->id  = $id;
        $this->form = $form;
    }

    /**
     * Add morph type.
     *
     * @param string $model
     * @return void
     */
    public function to(string $model)
    {
        $config = Crud::config($model);

        if (!$config->permissions['read']) {
            return;
        }

        $idDivider = MorphTo::ID_DIVIDER;
        $morphId = "{$this->id}{$idDivider}{$model}";
        $field = $this->form->registerField(MorphTo::class, $morphId);

        $this->types[$model] = collect([
            'name' => $config->names['singular'],
            'field' => $field
        ]);

        return $field;
    }

    public function registerMorphType()
    {
        # code...
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
