<?php

namespace Fjord\Form\Controllers;

use Illuminate\Http\Request;
use Fjord\TrackEdits\FormEdit;
use Fjord\Form\Database\FormField;
use Fjord\Form\FormFieldCollection;
use App\Http\Controllers\Controller;
use Fjord\Form\Database\FormRelation;
use Fjord\Support\Facades\FormLoader;
use Illuminate\Support\Facades\Route;
use Fjord\Form\Requests\FormUpdateRequest;

class FormController extends Controller
{
    public function update(Request $request, $id)
    {
        $formField = FormField::findOrFail($id);

        $formField->update($request->all());

        $edit = new FormEdit();
        $edit->fjord_user_id = fjord_user()->id;
        $edit->collection = $formField->collection;
        $edit->form_name = $formField->form_name;
        $edit->created_at = \Carbon\Carbon::now();
        $edit->save();

        $formField->append('last_edit');

        return $formField;
    }

    public function show(Request $request)
    {
        $routeSplit = explode('.', Route::currentRouteName());
        $formName = array_pop($routeSplit);
        $collection = last($routeSplit);

        $this->setForm($collection, $formName);

        $eloquentFormFields = $this->getFormFields($collection, $formName);

        $this->form->setPreviewRoute(
            new FormFieldCollection($eloquentFormFields['data'])
        );

        return view('fjord::app')->withComponent('fj-crud-show')
            ->withModels([
                'model' => $eloquentFormFields
            ])
            ->withTitle($this->form->title)
            ->withProps([
                'formConfig' => $this->form->toArray(),
                'headerComponents' => ['fj-crud-show-preview'],
                'controls' => [],
                'content' => ['fj-crud-show-form']
            ]);
    }

    protected function getFormFields($collection, $form_name)
    {
        $formFields = [];

        foreach ($this->form->form_fields as $key => $field) {

            $formFields[$key] = FormField::firstOrCreate(
                ['collection' => $collection, 'form_name' => $form_name, 'field_id' => $field->id],
                ['content' => $field->default ?? null]
            )->append('last_edit');

            if ($field->type == 'block') {
                $formFields[$key]->withRelation($field->id);
            }

            if ($field->type == 'relation') {
                $formFields[$key]->setFormRelation();
            }
            /*
            if($field['type'] == 'image') {
                $formFields[$key]->withRelation($field['id']);
            }
            */
        }

        return eloquentJs(collect($formFields), FormField::class);
    }

    protected function setForm($collection, $formName)
    {
        $formFieldInstance = new FormField();
        $formFieldInstance->collection = $collection;
        $formFieldInstance->form_name = $formName;

        $this->formPath = $formFieldInstance->form_fields_path;
        $this->form = FormLoader::load($this->formPath, FormField::class);
    }

    public function deleteRelation(FormUpdateRequest $request, $id, $relation, $relation_id)
    {
        // First we check if both crud model with the ID and relation model with 
        // the ID exist, no relations should be deleted for non existing records.
        $model = FormField::findOrFail($id);
        $formField = $model->findFormField($relation);
        if (!$formField) {
            abort(404);
        }
        $relationModel = $model->$relation()->findOrFail($relation_id);

        // Delete relation for form field "relation"
        return FormRelation::where('from_model_type', get_class($model))
            ->where('from_model_id', $id)
            ->where('to_model_type', get_class($relationModel))
            ->where('to_model_id', $relation_id)
            ->delete();
    }

    public function createRelation(FormUpdateRequest $request, $id, $relation, $relation_id)
    {
        // First we check if both crud model with the ID and relation model with 
        // the ID exist, no relations should be created for non existing records.
        $model = FormField::findOrFail($id);
        $formField = $model->findFormField($relation);
        if (!$formField) {
            abort(404);
        }
        $relationModel = $formField->query->findOrFail($relation_id);

        // Create relation for form field "relation"
        $query = [
            'from_model_type' => FormField::class,
            'from_model_id' => $id,
            'to_model_type' => $formField->model,
            'to_model_id' => $relation_id,
        ];
        if (FormRelation::where($query)->exists()) {
            abort(422, __f("fj.already_selected"));
        }
        return FormRelation::create($query);
    }
}
