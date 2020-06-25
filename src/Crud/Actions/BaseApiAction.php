<?php

namespace Fjord\Crud\Actions;

use Fjord\Crud\Field;
use Fjord\Crud\BaseForm;
use Illuminate\Http\Request;
use Fjord\Config\ConfigHandler;
use Fjord\Crud\Controllers\CrudController;
use Fjord\Crud\Controllers\FormController;

class BaseApiAction
{
    /**
     * Crud Config.
     *
     * @var \Fjord\Config\ConfigHandler
     */
    protected $config;

    /**
     * Crud Controller instance.
     *
     * @var CrudController|FormController
     */
    protected $controller;

    /**
     * Form instance.
     *
     * @var BaseForm
     */
    protected $form;

    /**
     * Field instance.
     *
     * @var Field
     */
    protected $field;

    /**
     * Model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Create new BaseApiAction instance.
     *
     * @param CrudController|FormController
     * @param \Fjord\Config\ConfigHandler $config
     */
    public function __construct($controller, ConfigHandler $config)
    {
        $this->controller = $controller;
        $this->config = $config;
    }

    /**
     * Execute action by type.
     *
     * @param string $form_type
     * @param string $field_id
     * @param string $type
     * @return mixed
     */
    public function execute($id, $form_type, $field_id, $type)
    {
        if (!method_exists($this, $type)) {
            abort(404);
        }

        $this->validateForm($form_type);
        $this->validateField($field_id);
        $this->setModel($id);

        return app()->call([$this, $type]);
    }

    /**
     * Set model.
     *
     * @param string|integer $id
     * @return void
     */
    protected function setModel($id)
    {
        $this->model = $this->controller->findOrFail($id);
    }

    /**
     * Validate form.
     *
     * @param Request $request
     * @return void
     */
    protected function validateForm($form_type)
    {
        if (!$this->config->has($form_type)) {
            abort(404);
        }

        $this->form = $this->config->{$form_type};

        if ($this->form instanceof BaseForm) {
            abort(404);
        }
    }

    /**
     * Validate field.
     *
     * @param string $field_id
     * @return void
     */
    protected function validateField($field_id)
    {
        $this->field = $this->form->findFieldOrFail($field_id);

        if ($this->fieldClass) {
            $this->validateFieldInstance($this->field, $this->fieldClass);
        }
    }

    /**
     * Get form type from request.
     *
     * @param Request $request
     * @return string
     */
    protected function getFormType(Request $request)
    {
        return $request->route('form');
    }

    /**
     * Get form.
     *
     * @return BaseForm
     */
    public function getForm()
    {
        $this->form;
    }

    /**
     * Validate field class.
     *
     * @param Field $field
     * @param string $expected
     * @return void
     */
    protected function validateFieldInstance(Field $field, $expected)
    {
        if (!$field instanceof $expected) {
            abort(404);
        }
    }

    /**
     * Make repository instance.
     *
     * @param Field $field
     * @return void
     */
    protected function makeRepository(Field $field)
    {
        return app()->make($this->repository, [
            'field' => $field
        ]);
    }

    /**
     * Call repository method.
     *
     * @param string $method
     * @param Field $field
     * @param array $params
     * @return mixed
     */
    protected function callRepository(string $method, Field $field, array $params = [])
    {
        return app()->call([$this->makeRepository($field), $method], $params);
    }
}
