<?php

namespace Ignite\Crud\Repositories;

use Ignite\Crud\CrudValidator;
use Ignite\Crud\Fields\Modal;
use Ignite\Crud\Requests\CrudUpdateRequest;

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

        return crud($model, $this->config);
    }
}
