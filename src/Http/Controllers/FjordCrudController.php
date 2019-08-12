<?php

namespace AwStudio\Fjord\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AwStudio\Fjord\Models\ModelContent;

class FjordCrudController extends Controller
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

        $this->config = getConfig('crud', $this->model)
            ->merge(['route' => $this->titlePlural]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->model::all();

        return view('fjord::vue')->withComponent('crud-index')
                                ->withTitle($this->titleSingular)
                                ->withProps([
                                    'data' => $data,
                                    'config' => $this->config
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
                 'model' => $model->eloquentJs(),
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
        $model = $this->model::withRelation('repeatables')
            ->withFormRelations()
            ->eloquentJs('findOrFail', $id);

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
        $data = $this->model::findOrFail($id);
        $data->update($request->all());

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
