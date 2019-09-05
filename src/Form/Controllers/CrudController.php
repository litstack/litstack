<?php

namespace AwStudio\Fjord\Form\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AwStudio\Fjord\Models\ModelContent;
use AwStudio\Fjord\Support\Facades\FormLoader;

class CrudController extends Controller
{
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
        $this->titlePlural = $this->titlePlural ?? lcfirst(str_plural($this->modelName));

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
    public function index()
    {
        $items = $this->model::with($this->getWiths())->eloquentJs('translatable', 'get');

        if(is_translatable($this->model)) {
            $items['data']->map(function($item) {
                return $item->append('translation');
            });
        }
        
        return view('fjord::vue')->withComponent('crud-index')
            ->withTitle($this->titleSingular)
            ->withModels([
                'items' => $items,
            ])
            ->withProps([
                'formConfig' => $this->getForm()
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create()
     {
         $className = $this->model;
         $model = new $className();

         return view('fjord::vue')->withComponent('crud-show')
             ->withTitle('edit ' . $this->titleSingular)
             ->withModels([
                 'model' => $model->eloquentJs('fjord'),
             ])
             ->withProps([
                 'formConfig' => $this->getForm($model)
             ]);;
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
    public function edit($id)
    {
        $eloquentModel = $this->model::with($this->getWiths())
            ->withRelation('blocks')
            ->withFormRelations()
            ->findOrFail($id)
            ->setFormRelations()
            ->eloquentJs('fjord');

        if(is_translatable($this->model)) {
            $eloquentModel['data']->append('translation');
        }

        $form = $this->getForm($eloquentModel['data']);
        $form->setPreviewRoute($eloquentModel['data']);

        return view('fjord::vue')->withComponent('crud-show')
            ->withTitle('edit ' . $this->titleSingular)
            ->withModels([
                'model' => $eloquentModel,
            ])
            ->withProps([
                'formConfig' => $form->toArray()
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
    public function destroy($id)
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

    protected function getWiths()
    {
        $withs = ['media'];
        if(is_translatable($this->model)) {
            $withs []= 'translations';
        }

        return $withs;
    }

}
