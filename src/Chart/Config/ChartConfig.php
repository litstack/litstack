<?php

namespace Ignite\Chart\Config;

use Ignite\Chart\Chart;
use Ignite\Support\Bootstrap;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class ChartConfig
{
    /**
     * Created at column.
     *
     * @var string
     */
    public $created_at = 'created_at';

    /**
     * Chart engine.
     *
     * @var string
     */
    public $engine = 'apex.area';

    /**
     * Default variant.
     *
     * @var string
     */
    public $variant = Bootstrap::PRIMARY;

    /**
     * The name of the relationship.
     *
     * @var string
     */
    public $relation;

    /**
     * Chart title.
     *
     * @return string
     */
    abstract public function title(): string;

    /**
     * Get model instance.
     *
     * @return void
     */
    public function getModel()
    {
        return new $this->model;
    }

    /**
     * Get initial query.
     *
     * @return Builder
     */
    public function query()
    {
        if (! request()->id && ! is_null($this->relation)) {
            abort(404, debug('Missing request key [id] for chart.'));
        }

        if (! $id = request()->id) {
            return $this->model::query();
        }

        if ($this->relation) {
            return $this->getRelationQuery($this->model::findOrFail($id));
        }

        return $this->model::where('id', $id);
    }

    /**
     * Get relationship query.
     *
     * @param  Builder  $query
     * @return Relation
     */
    protected function getRelationQuery($model)
    {
        return $model->{$this->relation}();
    }

    /**
     * Mount.
     *
     * @param Chart $chart
     *
     * @return void
     */
    public function mount(Chart $chart)
    {
        //
    }

    /**
     * Add count select to query.
     *
     * @param  Builder $query
     * @return Builder
     */
    protected function count($query)
    {
        $this->resultResolver = fn (Collection $values) => $values->sum();

        return (clone $query)->select(
            DB::raw('COUNT(*) as value')
        );
    }

    /**
     * Add average select to query.
     *
     * @param  Builder $query
     * @param  string  $column
     * @return Builder
     */
    protected function average($query, string $column)
    {
        $this->resultResolver = function (Collection $values) {
            return $values->filter(fn ($value) => $value > 0)
                ->values()
                ->avg() ?: 0;
        };

        return (clone $query)->select(
            DB::raw("AVG({$column}) as value")
        );
    }

    /**
     * Add sum select to query.
     *
     * @param  Builder $query
     * @param  string  $column
     * @return Builder
     */
    protected function sum($query, string $column)
    {
        $this->resultResolver = fn (Collection $values) => $values->sum();

        return (clone $query)->select(
            DB::raw("SUM({$column}) as value")
        );
    }

    /**
     * Add min select to query.
     *
     * @param  Builder $query
     * @param  string  $column
     * @return Builder
     */
    protected function min($query, string $column)
    {
        $this->resultResolver = fn (Collection $values) => $values->min();

        return (clone $query)->select(
            DB::raw("MIN({$column}) as value")
        );
    }

    /**
     * Add max select to query.
     *
     * @param  Builder $query
     * @param  string  $column
     * @return Builder
     */
    protected function max($query, string $column)
    {
        $this->resultResolver = fn (Collection $values) => $values->max();

        return (clone $query)->select(
            DB::raw("MAX({$column}) as value")
        );
    }
}
