<?php

namespace AwStudio\Fjord\Form\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AwStudio\Fjord\Models\ModelContent;
use AwStudio\Fjord\Fjord\Controllers\Traits\CanHaveFjordExtensions;
use AwStudio\Fjord\Support\Facades\FormLoader;
use AwStudio\Fjord\Form\Requests\CrudCreateRequest;
use AwStudio\Fjord\Form\Requests\CrudReadRequest;
use AwStudio\Fjord\Form\Requests\CrudUpdateRequest;
use AwStudio\Fjord\Form\Requests\CrudDeleteRequest;

class CrudController extends Controller
{
    use CanHaveFjordExtensions,
        Traits\CrudIndex,
        Traits\CrudRelations;

    // The Model (Class)Name, e.g. Post
    protected $modelName;

    // The Model Class e.g. App\Models\Post
    protected $model;

    // The Model's singular lowercase title
    protected $titleSingular;

    // The Model's plural lowercase title
    protected $titlePlural;

    // Is the Model translatable
    protected $translatable;

    // The Model's config
    protected $config;

    public function __construct()
    {
        $this->titleSingular = $this->titleSingular ?? lcfirst($this->modelName);
        $this->titlePlural = $this->titlePlural ?? \Str::snake(\Str::plural($this->modelName));

        $this->model = "App\\Models\\" . ucfirst($this->modelName);

        // check, if the mode is translatable
        $reflect = new \ReflectionClass($this->model);
        if ($reflect->implementsInterface('Astrotomic\Translatable\Contracts\Translatable')){
            $this->translatable = true;
        }else{
            $this->translatable = false;
        }

        $model = $this->model;
        $data = new $model();

        $translatedAttributes = $this->translatable ? $data->translatedAttributes() : null;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CrudReadRequest $request)
    {
        return view('fjord::vue')->withComponent('fj-crud-index')
            ->withTitle($this->titleSingular)
            ->withProps([
                'formConfig' => $this->getForm()->toArray(),
                'actions' => $this->getExtensions('index.actions'),
                'globalActions' => $this->getExtensions('index.globalActions'),
                'recordActions' => $this->getExtensions('index.recordActions'),
            ]);
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

        return view('fjord::vue')->withComponent('fj-crud-show')
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
    public function edit(CrudUpdateRequest $request, $id)
    {
        // initial query
        if(array_key_exists('query', $this->getForm()->toArray()['index'])){
            $query = $this->getForm()->toArray()['index']['query'];
        }else{
            $query = new $this->model;
        }

        $query = $query->with($this->getWiths());

        if(array_key_exists('load', $this->getForm()->toArray()['index'])){
            $query->with(array_keys($this->getForm()->toArray()['index']['load']));
        }

        $model = $query->withFormRelations()
            ->findOrFail($id);

        if(is_translatable($this->model)) {
            $model->append('translation');
        }

        foreach($model->form_fields as $form_field) {
            if($form_field->type == 'block') {
                $model->withRelation($form_field->id);
            }
        }

        $eloquentModel = $model->eloquentJs('fjord');

        $eloquentModel['data']->withRelation('blocks');

        $form = $this->getForm($eloquentModel['data']);
        $form->setPreviewRoute($eloquentModel['data']);

        $previous = $this->model::where('id', '<', $id)->orderBy('id','desc')->select('id')->first()->id ?? null;
        $next = $this->model::where('id', '>', $id)->orderBy('id')->select('id')->first()->id ?? null;

        return view('fjord::vue')->withComponent('fj-crud-show')
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
                'actions' => $this->getExtensions('show.actions'),
                'controls' => $this->getExtensions('show.controls'),
                'content' => $this->getExtensions('show.content')
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

        if(is_translatable($this->model)) {
            $item->append('translation');
        }

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
        if(! $model) {
            $model = with(new $this->model);
        }

        return FormLoader::load($model->form_fields_path, $this->model);
    }
}
