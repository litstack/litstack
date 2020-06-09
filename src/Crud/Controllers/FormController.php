<?php

namespace Fjord\Crud\Controllers;

use Illuminate\Http\Request;
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
     * Authorize request for permission operation and authenticated fjord-user.
     * Operations: read, update
     *
     * @param \Fjord\User\Models\FjordUser $user
     * @param string $operation
     * @return boolean
     */
    public function authorize(FjordUser $user, string $operation): bool
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
            if (!$config['permissions']['update']) {
                $field->readonly();
            }
        }

        $model = FormField::firstOrCreate([
            'collection' => $this->config->collection,
            'form_name' => $this->config->formName,
        ]);

        return view('fjord::app')->withComponent($this->config->component)
            ->withTitle("Form " . $this->config->names['singular'])
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
