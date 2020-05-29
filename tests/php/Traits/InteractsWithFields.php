<?php

namespace FjordTest\Traits;

use Illuminate\Database\Eloquent\Model;

trait InteractsWithFields
{
    /**
     * Get field instance from class.
     *
     * @param string $fieldClass
     * @param string $id
     * @param string $model
     * @return mixed
     */
    public function getField(string $fieldClass, string $id = 'dummy_field', string $model = null)
    {
        if (!$model) {
            $model = DummyFieldModel::class;
        }
        return new $fieldClass(
            $id,
            $model,
            'dummy_route_prefix',
            new DummyFieldForm
        );
    }
}

class DummyFieldModel extends Model
{
}

class DummyFieldForm
{
}
