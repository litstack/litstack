<?php

namespace AwStudio\Fjord\Form\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AwStudio\Fjord\Models\ModelContent;

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
        $withs = ['media'];
        if(is_translateable($this->model)) {
            $withs []= 'translations';
        }

        $items = $this->model::with($withs)->eloquentJs('translatable', 'get');

        if(is_translateable($this->model)) {
            $items['data']->map(function($item) {
                return $item->append('translation');
            });
        }
        /*
        get()->map(function($model) {
            return $model->append('translation');
        });
        */

        return view('fjord::vue')->withComponent('crud-index')
                                ->withTitle($this->titleSingular)
                                ->withModels([
                                    'items' => $items,
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
             ]);
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
        $withs = ['media'];
        if(is_translateable($this->model)) {
            $withs []= 'translations';
        }

        $model = $this->model::with($withs)
            ->withRelation('blocks')
            ->withFormRelations()
            ->findOrFail($id)
            ->setFormRelations()
            ->eloquentJs('fjord');

        if(is_translateable($this->model)) {
            $model['data']->append('translation');
        }

        //dd($model['data']);

        return view('fjord::vue')->withComponent('crud-show')
            ->withTitle('edit ' . $this->titleSingular)
            ->withModels([
                'model' => $model,
            ])
            ->withProps([
                't' => 's'
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
        $item = $this->model::findOrFail($id);
        $item->update($request->all());

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

}
