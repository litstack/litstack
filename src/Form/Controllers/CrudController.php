<?php

namespace Fjord\Form\Controllers;

use ReflectionClass;
use Illuminate\Http\Request;
use Fjord\Models\ModelContent;
use Fjord\Fjord\Models\FjordUser;
use Fjord\Form\Database\FormBlock;
use Fjord\Support\Facades\FormLoader;
use Fjord\Form\Requests\CrudReadRequest;
use Fjord\Form\Requests\CrudCreateRequest;
use Fjord\Form\Requests\CrudDeleteRequest;
use Fjord\Form\Requests\CrudUpdateRequest;

abstract class CrudController
{
    use Traits\CrudIndex,
        Traits\CrudRelations,
        Traits\EloquentModel;
    /**
     * The Model Class e.g. App\Models\Post
     *
     * @var string
     */
    protected $model;

    /**
     * The Model's singular lowercase title.
     *
     * @var string
     */
    protected $titleSingular;

    /**
     * The Model's plural lowercase title.
     *
     * @var string
     */
    protected $titlePlural;

    /**
     * Is the Model translatable.
     *
     * @var boolean
     */
    protected $translatable;

    /**
     * The Model's config.
     *
     * @var array
     */
    protected $config;

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
        $reflect = new ReflectionClass($this->model);
        $modelBaseName = $reflect->getShortName();

        // Names.
        $this->titleSingular = $this->titleSingular ?? lcfirst($modelBaseName);
        $this->titlePlural = $this->titlePlural ?? \Str::snake(\Str::plural($modelBaseName));

        // Translatable.
        $this->translatable = is_translatable($this->model);
    }

    public function all(CrudReadRequest $request)
    {
        return $this->model::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CrudReadRequest $request)
    {
        return view('fjord::app')->withComponent('fj-crud-index')
            ->withTitle($this->titleSingular)
            ->withProps([
                'formConfig' => $this->getForm()->toArray(),
                'actions' => ['fj-crud-index-delete-all'],
                //'actions' => $this->getExtensions('index.actions'),
                'globalActions' => [],
                'recordActions' => []
                //'globalActions' => $this->getExtensions('index.globalActions'),
                //'recordActions' => $this->getExtensions('index.recordActions'),
            ]);
    }

    public function show(CrudUpdateRequest $request, $id)
    {
        return $this->eloquentModel($request, $id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CrudCreateRequest $request)
    {
        $className = $this->model;
        $model = new $className();

        return view('fjord::app')->withComponent('fj-crud-show')
            ->withTitle('edit ' . $this->titleSingular)
            ->withModels([
                'model' => $model->eloquentJs('fjord'),
            ])
            ->withProps([
                'formConfig' => $this->getForm($model)->toArray(),
                'content' => ['fj-crud-show-form']
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrudCreateRequest $request)
    {
        $data = $this->model::create($request->all());

        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CrudReadRequest $request, $id)
    {
        $eloquentModel = $this->eloquentModel($request, $id);

        $form = $this->getForm($eloquentModel['data']);
        $form->setPreviewRoute($eloquentModel['data']);

        $previous = $this->model::where('id', '<', $id)->orderBy('id', 'desc')->select('id')->first()->id ?? null;
        $next = $this->model::where('id', '>', $id)->orderBy('id')->select('id')->first()->id ?? null;

        return view('fjord::app')->withComponent('fj-crud-show')
            ->withTitle('edit ' . $this->titleSingular)
            ->withModels([
                'model' => $eloquentModel,
            ])
            ->withProps([
                'formConfig' => $form->toArray(),
                'nearItems' => [
                    'next' => $next,
                    'previous' => $previous
                ],
                'headerComponents' => ['fj-crud-show-preview'],
                'controls' => [],
                'content' => ['fj-crud-show-form']
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CrudUpdateRequest $request, $id)
    {
        $item = $this->model::with($this->getWiths())->findOrFail($id);
        $item->update($request->all());

        if (is_translatable($this->model)) {
            $item->append('translation');
        }

        $item->load('last_edit');

        return $item;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrudDeleteRequest $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $item->delete();
    }

    protected function getForm($model = null)
    {
        if (!$model) {
            $model = with(new $this->model);
        }
        return FormLoader::load($model->form_fields_path, $this->model);
    }

    /**
     * Store new form_block.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function storeBlock(CrudUpdateRequest $request, $id)
    {
        $model = $this->model::findOrFail($id);

        $block = new FormBlock();
        $block->type = $request->type;
        $block->model_type = $this->model;
        $block->model_id = $model->id;
        $block->field_id = $request->field_id;
        $block->value = $request->value;
        $block->order_column = $request->order_column;
        $block->save();

        return $block->eloquentJs();
    }

    /**
     * Update form_block.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function updateBlock(CrudUpdateRequest $request, $id, $block_id)
    {
        $block = FormBlock::where('model_type', $this->model)
            ->where('model_id', $id)->findOrFail($block_id);

        $block->update($request->all());

        return $block;
    }

    /**
     * Update form_block.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function destroyBlock(CrudUpdateRequest $request, $id, $block_id)
    {
        $block = FormBlock::where('model_type', $this->model)
            ->where('model_id', $id)->findOrFail($block_id);

        return $block->delete();
    }
}
