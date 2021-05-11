<?php

namespace Ignite\Crud\Repositories;

use Ignite\Crud\CrudValidator;
use Ignite\Crud\Fields\Modal;
use Ignite\Crud\Fields\Relations\LaravelRelationField;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Http\Request;

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

    /**
     * Get child field for relation fields.
     *
     * @param  Request $request
     * @param  string  $field_id
     * @return Field
     */
    public function getField(Request $request, $field_id)
    {
        return $this->field->form->findField($field_id)
            ?: abort(404, debug("Coulnd't find field [$field_id]"));
    }

    /**
     * Get repeatable model.
     *
     * @param  Request    $request
     * @param  mixed      $model
     * @return Repeatable
     */
    public function getModel(Request $request, $model, $childRepository)
    {
        return $model;
    }
}
