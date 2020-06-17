<?php

namespace Fjord\Chart\Config;

use Fjord\Chart\Chart;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

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
    public $engine = 'apex';

    /**
     * Default variant.
     *
     * @var string
     */
    public $variant = 'primary';

    /**
     * Chart title.
     *
     * @return string
     */
    abstract public function title(): string;

    /**
     * Mount.
     *
     * @param Chart $chart
     * @return void
     */
    public function mount(Chart $chart)
    {
        //
    }

    /**
     * Add count select to query.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function count($query)
    {
        return (clone $query)->select(
            DB::raw("COUNT(*) as value")
        );
    }

    /**
     * Add average select to query.
     *
     * @param Builder $query
     * @param string $column
     * @return Builder
     */
    protected function average($query, string $column)
    {
        return (clone $query)->select(
            DB::raw("AVG({$column}) as value")
        );
    }

    /**
     * Add sum select to query.
     *
     * @param Builder $query
     * @param string $column
     * @return Builder
     */
    protected function sum($query, string $column)
    {
        return (clone $query)->select(
            DB::raw("SUM({$column}) as value")
        );
    }

    /**
     * Add min select to query.
     *
     * @param Builder $query
     * @param string $column
     * @return Builder
     */
    protected function min($query, string $column)
    {
        return (clone $query)->select(
            DB::raw("MIN({$column}) as value")
        );
    }

    /**
     * Add max select to query.
     *
     * @param Builder $query
     * @param string $column
     * @return Builder
     */
    protected function max($query, string $column)
    {
        return (clone $query)->select(
            DB::raw("MAX({$column}) as value")
        );
    }
}
