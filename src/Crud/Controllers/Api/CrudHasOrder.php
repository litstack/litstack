<?php

namespace Fjord\Crud\Controllers\Api;

trait CrudHasOrder
{
    /**
     * Order models.
     *
     * @param Collection $models
     * @param Field $field
     * @param array $ids
     * @return void
     */
    public function order($models, $field, $ids)
    {
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
