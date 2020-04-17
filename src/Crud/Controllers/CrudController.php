<?php

namespace Fjord\Crud\Controllers;

use Illuminate\Http\Request;
use Fjord\Fjord\Models\FjordUser;
use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudCreateRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;

abstract class CrudController
{
    use Api\HasIndex,
        Api\HasRelations,
        Api\HasBlocks,
        Concerns\HasConfig;

    /**
     * The Model Class e.g. App\Models\Post
     *
     * @var string
     */
    protected $model;

    /**
     * Authorize request for operation.
     *
     * @param \Fjord\Fjord\Models\FjordUser $user
     * @param string $operation
     * @return boolean
     */
    abstract public function authorize(FjordUser $user, string $operation): bool;

    /**
     * Show Crud index.
     *
     * @param Request $request
     * @return View
     */
    public function index(CrudReadRequest $request)
    {
        $config = $this->model::config()
            ->get(
                'index',
                'route_prefix',
                'names',
                'search',
                'sortByDefault'
            );

        return view('fjord::app')
            ->withComponent('fj-crud-index')
            ->withProps([
                'config' => $config,
                'headerComponents' => [],
            ]);
    }

    /**
     * Show Crud create.
     *
     * @param CrudCreateRequest $request
     * @return void
     */
    public function create(CrudCreateRequest $request)
    {
        $config = $this->config()->get('form', 'names', 'permissions', 'route_prefix');
        $model = new $this->model;

        return view('fjord::app')
            ->withComponent('fj-crud-show')
            ->withModels([
                'model' => $model->eloquentJs()
            ])
            ->withProps([
                'config' => $config,
                'headerComponents' => []
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CrudReadRequest $request, $id)
    {
        $model = $this->query()->findOrFail($id);

        // Load eloquentJs blocks.
        foreach ($model->fields as $field) {
            if ($field instanceof Blocks) {
                $model->withRelation($field->id);
            }
        }

        $config = $this->model::config()->get(
            'form',
            'route_prefix',
            'names',
            'permissions'
        );

        $previous = $this->model::where('id', '<', $id)->orderBy('id', 'desc')->select('id')->first()->id ?? null;
        $next = $this->model::where('id', '>', $id)->orderBy('id')->select('id')->first()->id ?? null;

        return view('fjord::app')->withComponent('fj-crud-show')
            ->withTitle('Edit ' . $this->model::config()->names['singular'])
            ->withModels([
                'model' => $model->eloquentJs(),
            ])
            ->withProps([
                'config' => $config,
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
     * Update Crud model.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @return mixed $model
     */
    public function update(CrudUpdateRequest $request, $id)
    {
        $model = $this->query()->findOrFail($id);

        $model->update($request->all());

        return $model;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Fjord\Crud\Requests\CrudCreateRequest  $request
     * @return mixed
     */
    public function store(CrudCreateRequest $request)
    {
        $model = $this->model::create($request->all());

        return $model;
    }
}
