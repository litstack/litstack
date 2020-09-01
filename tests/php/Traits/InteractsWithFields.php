<?php

namespace Tests\Traits;

use Illuminate\Database\Eloquent\Model;

trait InteractsWithFields
{
    /**
     * Get field instance from class.
     *
     * @param string     $fieldClass
     * @param string     $id
     * @param string     $model
     * @param mixed|null $form
     *
     * @return mixed
     */
    public function getField(string $fieldClass, string $id = 'dummy_field', string $model = null, $form = null)
    {
        if (! $model) {
            $model = DummyFieldModel::class;
        }

        return new $fieldClass(
            $id,
            $model,
            'dummy_route_prefix',
            $form ? $form : new DummyFieldForm()
        );
    }
}

class DummyFieldModel extends Model
{
}

class DummyFieldForm
{
}
