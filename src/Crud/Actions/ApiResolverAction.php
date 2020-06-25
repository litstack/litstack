<?php

namespace Fjord\Crud\Actions;

use Fjord\Crud\Field;
use Fjord\Crud\BaseForm;
use Illuminate\Http\Request;
use Fjord\Config\ConfigHandler;
use Fjord\Crud\Controllers\CrudController;
use Fjord\Crud\Controllers\FormController;
use Fjord\Crud\Repositories\ListRepository;

class ApiResolverAction
{
    protected $actions = [
        'list' => ListRepository::class,
    ];

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
    // public function __construct($controller, ConfigHandler $config)
    // {
    //     $this->controller = $controller;
    //     $this->config = $config;
    // }

    /**
     * Execute action by type.
     *
     * @param string $form_type
     * @param string $field_id
     * @param string $type
     * @return mixed
     */
    public function execute(Request $request, $id, $form_type, $field_id, $action, $type)
    {
        if (!$this->hasAction($action)) {
            abort(404);
        }

        $repository = $this->actions[$type];

        if (!method_exists($repository, $type)) {
            abort(404);
        }

        $form = $this->getForm($form_type);
        $field = $this->getField($form, $field_id);
        $model = $this->getModel($id);

        $instance = new $repository[$action](
            $this->controller,
            $this->config,
            $field,
            $model
        );

        return app()->call([$instance, $type], [
            'payload' => (object) ($request->payload ?: []),
        ]);
    }

    /**
     * Set model.
     *
     * @param string|integer $id
     * @return void
     */
    protected function getModel($id)
    {
        $this->model = $this->controller->findOrFail($id);
    }

    /**
     * Validate form.
     *
     * @param Request $request
     * @return void
     */
    protected function getForm($form_type)
    {

        if (!$this->config->has($form_type)) {
            abort(404);
        }

        $form = $this->config->{$form_type};

        if (!$form instanceof BaseForm) {
            abort(404);
        }

        return $form;
    }

    /**
     * Validate field.
     *
     * @param string $field_id
     * @return void
     */
    protected function getField($form, $field_id)
    {
        $field = $form->findFieldOrFail($field_id);

        // if ($this->fieldClass) {
        //     $this->validateFieldInstance($this->field, $this->fieldClass);
        // }

        return $field;
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
