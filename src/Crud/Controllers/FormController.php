<?php

namespace Fjord\Crud\Controllers;

use Fjord\Crud\Models\FormEdit;
use Fjord\Crud\Models\FormField;
use Fjord\User\Models\FjordUser;
use Fjord\Crud\Fields\Blocks\Blocks;
use Illuminate\Support\Facades\Route;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\FormReadRequest;
use Illuminate\Database\Eloquent\Builder;
use Fjord\Crud\Requests\CrudCreateRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Requests\FormUpdateRequest;

abstract class FormController
{
    use Api\CrudBaseApi,
        Api\CrudHasIndex,
        Api\CrudHasRelations,
        Api\CrudHasBlocks,
        Api\CrudHasMedia,
        Api\CrudHasOrder,
        Api\CrudHasModal,
        Concerns\ManagesConfig,
        Concerns\ManagesForm,
        Concerns\ManagesCrud;

    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = FormField::class;

    /**
     * Authorize request for operation.
     *
     * @param FjordUser $user
     * @param string $operation
     * @return boolean
     */
    abstract public function authorize(FjordUser $user, string $operation): bool;

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
     * @param int $id
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
     * @return View $view
     */
    public function edit(CrudReadRequest $request)
    {
        // Getting collection and formName from route.
        $routeSplit = explode('.', Route::currentRouteName());
        array_pop($routeSplit);
        $formName = array_pop($routeSplit);
        $collection = last($routeSplit);

        $configInstance = fjord()->config("form.{$collection}.{$formName}");

        $config = $configInstance->get(
            'names',
            'show',
            'permissions',
            'route_prefix',
            'expandContainer'
        );
        $config['form'] = $config['show'];
        unset($config['show']);
        $config['expand'] = $config['expandContainer'];

        // Get preview route.
        if ($configInstance->hasMethod('previewRoute')) {
            $config['preview_route'] = $configInstance->previewRoute();
        }

        // Set readonly if the user has no update permission for this crud.
        foreach ($config['form']->getRegisteredFields() as $field) {
            if (!$config['permissions']['update']) {
                $field->readonly();
            }
        }

        $model = FormField::firstOrCreate([
            'collection' => $configInstance->collection,
            'form_name' => $configInstance->formName,
        ]);

        return view('fjord::app')->withComponent($this->config->component)
            ->withTitle("Form " . $configInstance->names['singular'])
            ->withProps([
                'crud-model' => crud($model),
                'config' => $config,
                'header-components' => ['fj-crud-preview'],
                'controls' => [],
            ]);
    }

    /**
     * Deny storing form FormField model.
     *
     * @param  \Fjord\Crud\Requests\CrudCreateRequest  $request
     * @return mixed
     */
    public function store(CrudCreateRequest $request)
    {
        //
    }

    /**
     * Deny filling attributes to FormField Model.
     *
     * @return void
     */
    public function fillModelAttributes($model, $request, $fields)
    {
        return;
    }
}
