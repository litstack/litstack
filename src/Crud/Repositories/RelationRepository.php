<?php

namespace Ignite\Crud\Repositories;

use Ignite\Crud\Fields\Relations\LaravelRelationField;
use Ignite\Crud\Fields\Relations\OneRelationField;
use Ignite\Crud\Requests\CrudReadRequest;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Ignite\Support\IndexTable;
use Illuminate\Database\Eloquent\Collection;

class RelationRepository extends BaseFieldRepository
{
    /**
     * LaravelRelationField field instance.
     *
     * @var LaravelRelationField
     */
    protected $field;

    /**
     * Create new BlockRepository instance.
     */
    public function __construct($config, $controller, $form, LaravelRelationField $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Load selectable relations.
     *
     * @param  CrudReadRequest $request
     * @param  mixed           $model
     * @return array
     */
    public function index(CrudReadRequest $request, $model)
    {
        $index = IndexTable::query($this->field->getQuery())
            ->request($request)
            ->search($this->field->search)
            ->get();

        $index['items'] = crud($index['items'], $this->field);

        return $index;
    }

    /**
     * Load selected relations.
     *
     * @param  CrudReadRequest $request
     * @param  mixed           $model
     * @return void
     */
    public function load(CrudReadRequest $request, $model)
    {
        if ($this->field instanceof OneRelationField) {
            $items = $this->field->getResults($model);
            $items = new Collection($items ? [$items] : []);
            $relations = collect([
                'count' => 0,
                'items' => $items,
            ]);
        } else {
            $query = $this->field->getRelationQuery($model);
            $relations = IndexTable::query($query)
                ->request($request)
                ->search($this->field->search)
                ->only(['filter', 'paginate', 'search'])
                ->get();
        }

        $relations['items'] = crud(
            $relations['items'], $this->field
        );

        return $relations;
    }

    /**
     * Order relations.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @return void
     */
    public function order(CrudUpdateRequest $request, $model)
    {
        $ids = $request->ids ?? abort(404, debug('Missing parameter [ids].'));

        $query = $this->field->getRelationQuery($model);

        $this->orderField($query, $this->field, $ids);
    }
}
