<?php

namespace Lit\Crud\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Lit\Crud\Models\Form;
use Lit\Crud\Models\Form as FormModel;
use Lit\Crud\Requests\CrudCreateRequest;
use Lit\Crud\Requests\CrudReadRequest;
use Lit\Crud\Requests\FormReadRequest;
use Lit\User\Models\LitUser;

abstract class FormController extends CrudBaseController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = FormModel::class;

    /**
     * Authorize request for permission operation and authenticated lit-user.
     * Operations: read, update.
     *
     * @param \Lit\User\Models\LitUser $user
     * @param string                   $operation
     *
     * @return bool
     */
    public function authorize(LitUser $user, string $operation): bool
    {
        return true;
    }

    /**
     * Create new CrudController instance.
     */
    public function __construct()
    {
        $this->config = $this->loadConfig();
    }

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
        $model = $this->query()->findOrFail($id);
        $model->last_edit;

        return crud(
            $model
        );
    }

    /**
     * Initial query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): Builder
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
            'names',
            'show',
            'permissions',
            'route_prefix',
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

        $model = Form::firstOrCreate([
            'config_type' => get_class($this->config->getConfig()),
        ], [
            'form_name'  => $this->config->formName,
            'collection' => $this->config->collection,
            'form_type'  => 'show',
        ]);

        $page = $this->config->show->bind([
            'crud-model' => crud($model),
            'config'     => $config,
        ]);

        return $page;
    }

    /**
     * Deny storing form Form model.
     *
     * @param \Lit\Crud\Requests\CrudCreateRequest $request
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
