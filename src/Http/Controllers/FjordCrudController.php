<?php

namespace AwStudio\Fjord\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

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

    // The Model's parameters
    protected $parameters;

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

        $this->parameters = collect([
            'translatable' => $this->translatable,
            'translatedAttributes' => $translatedAttributes,
            'route' => $this->titlePlural,
            'fields' => collect(config('fjord-crud.' . $this->titlePlural)),
            'languages' => config('translatable.locales'),
            'title' => $this->titleSingular
        ]);
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
                                    'parameters' => $this->parameters
                                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = $this->model;
        $data = new $model();

        return view('fjord::vue')->withComponent('crud-show')
                                ->withTitle('add ' . $this->titleSingular)
                                ->withProps([
                                    'data' => $data,
                                    'method' => 'post',
                                    'parameters' => $this->parameters
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
        $data = $this->model::findOrFail($id);

        return view('fjord::vue')->withComponent('crud-show')
                                ->withTitle('edit ' . $this->titleSingular)
                                ->withProps([
                                    'data' => $data,
                                    'method' => 'put',
                                    'parameters' => $this->parameters
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
