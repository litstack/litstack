<?php

namespace Tests\Traits;

use Ignite\Crud\BaseForm;
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

        $field = new $fieldClass($id);

        $field->setModel($model);
        $field->setRoutePrefix('dummy_route_prefix');
        $field->setParentForm($form ? $form : new DummyFieldForm($model));

        return $field;
    }
}

class DummyFieldModel extends Model
{
}

class DummyFieldForm extends BaseForm
{
}
