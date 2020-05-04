<?php

namespace Fjord\Crud\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;

trait CrudHasOrder
{
    /**
     * Order models.
     *
     * @param Builder $query
     * @param Field $field
     * @param array $ids
     * @return void
     */
    protected function orderField(Builder $query, $field, $ids)
    {
        $models = $query->whereIn('ids', $ids)->get();

        $oderColumn = $field->orderColumn ?? 'order_column';

        foreach ($ids as $order => $id) {
            $model = $models->where('id', $id)->first();

            if (!$model) {
                continue;
            }
            if ($model->pivot) {
                $model->pivot->{$oderColumn} = $order;
                $model->pivot->save();
            } else {
                $model->{$oderColumn} = $order;
                $model->save();
            }
        }
    }
}
