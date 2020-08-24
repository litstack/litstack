<?php

namespace Lit\Crud\Repositories;

use Lit\Crud\CrudValidator;
use Lit\Crud\Fields\Modal;
use Lit\Crud\Requests\CrudUpdateRequest;

class ModalRepository extends BaseFieldRepository
{
    /**
     * LaravelRelationField field instance.
     *
     * @var Modal
     */
    protected $field;

    /**
     * Create new BlockRepository instance.
     */
    public function __construct($config, $controller, $form, Modal $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Update model form.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     *
     * @return void
     */
    public function update(CrudUpdateRequest $request, $model, $payload)
    {
        CrudValidator::validate(
            (array) $payload,
            $this->field->form,
            CrudValidator::UPDATE
        );

        $this->fillAttributesToModel($model, (array) $payload);
        $attributes = $this->formatAttributes((array) $payload, $this->form->getRegisteredFields());

        $this->controller->fillOnUpdate($model);

        $model->update($attributes);

        if ($model->last_edit) {
            $model->load('last_edit');
        }

        return crud($model);
    }
}
