<?php

namespace Fjord\Form\Controllers\Traits;

trait EloquentModel
{
    public function eloquentModel($request, $id)
    {
        // initial query
        if (array_key_exists('query', $this->getForm()->toArray()['index'])) {
            $query = $this->getForm()->toArray()['index']['query'];
        } else {
            $query = new $this->model;
        }

        $query = $query->with($this->getWiths());

        if (array_key_exists('load', $this->getForm()->toArray()['index'])) {
            $query->with(array_keys($this->getForm()->toArray()['index']['load']));
        }

        $model = $query->withFormRelations()
            ->findOrFail($id);

        if (is_translatable($this->model)) {
            $model->append('translation');
        }

        foreach ($model->form_fields as $form_field) {
            if ($form_field->type == 'block') {
                $model->withRelation($form_field->id);
            }
        }

        $eloquentModel = $model->eloquentJs('fjord');

        $eloquentModel['data']->withRelation('blocks');

        return $eloquentModel;
    }
}
