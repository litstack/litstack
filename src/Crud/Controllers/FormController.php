<?php

namespace Ignite\Crud\Controllers;

use Ignite\Crud\Models\Form;
use Ignite\Crud\Models\Form as FormModel;
use Ignite\Crud\Requests\CrudCreateRequest;
use Ignite\Crud\Requests\CrudReadRequest;
use Ignite\Crud\Requests\FormReadRequest;

abstract class FormController extends CrudBaseController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = FormModel::class;

    /**
     * Load model.
     *
     * @param CrudReadRequest $request
     * @param int             $id
     *
     * @return array
     */
    public function load(CrudReadRequest $request, $id)
    {
        $model = $this->getQuery()->findOrFail($id);

        return crud(
            $model, $this->config
        );
    }

    /**
     * Initial query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query($query)
    {
        return $this->model::query();
    }

    /**
     * Edit form.
     *
     * @param FormReadRequest $request
     *
     * @return View $view
     */
    public function show(CrudReadRequest $request)
    {
        $config = $this->config->get(
            'names', 'show', 'permissions', 'route_prefix',
        );
        $config['form'] = $config['show'];
        unset($config['show']);

        // Get preview route.
        if ($this->config->hasMethod('previewRoute')) {
            $config['preview_route'] = $this->config->previewRoute();
        }

        // Set readonly if the user has no update permission for this crud.
        foreach ($config['form']->getRegisteredFields() as $field) {
            if (! $config['permissions']['update']) {
                $field->readonly();
            }
        }

        $model = $this->config->getNamespace()::load();

        $page = $this->config->show->bind([
            'crud-model' => crud($model, $this->config),
            'config'     => $config,
        ]);
        $page->bindToView(['config' => $this->config]);

        return $page;
    }

    /**
     * Deny storing form Form model.
     *
     * @param \Ignite\Crud\Requests\CrudCreateRequest $request
     *
     * @return mixed
     */
    public function store(CrudCreateRequest $request)
    {
        //
    }

    /**
     * Deny filling attributes to Form Model.
     *
     * @return void
     */
    public function fillModelAttributes($model, $request, $fields)
    {
    }
}
