<?php

namespace Fjord\Form\Controllers;

use Fjord\TrackEdits\FormEdit;
use Fjord\Fjord\Models\FjordUser;
use Fjord\Form\Database\FormBlock;
use Fjord\Form\Database\FormField;
use Fjord\Form\FormFieldCollection;
use Fjord\Form\Database\FormRelation;
use Fjord\Support\Facades\FormLoader;
use Illuminate\Support\Facades\Route;

use Fjord\Crud\Requests\FormReadRequest;
use Fjord\Crud\Requests\FormUpdateRequest;

abstract class FormController
{
    use Traits\FormMedia,
        Traits\FormRelations;
    //Traits\HasResource,
    //Concerns\HasConfig;

    /**
     * Authorize request for operation.
     *
     * @param FjordUser $user
     * @param string $operation
     * @return boolean
     */
    abstract public function authorize(FjordUser $user, string $operation): bool;

    /**
     * Create new FormController instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Update form_field.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @return mixed $model
     */
    public function update(CrudUpdateRequest $request, $id)
    {
        $model = $this->query()->findOrFail($id);

        $model->update($request->all());

        $edit = new FormEdit();
        $edit->fjord_user_id = fjord_user()->id;
        $edit->collection = $formField->collection;
        $edit->form_name = $formField->form_name;
        $edit->created_at = \Carbon\Carbon::now();
        $edit->save();

        $formField->append('last_edit');

        return $formField;
    }

    /**
     * Show form.
     *
     * @param FormReadRequest $request
     * @return View $view
     */
    public function show(FormReadRequest $request)
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

    /**
     * Get form fields.
     *
     * @param string $collection
     * @param string $form_name
     * @return void
     */
    protected function getFormFields(string $collection, string $form_name)
    {
        $formFields = [];

        $i = 0;
        foreach ($this->form->form_fields as $key => $field) {
            if (!$field['authorize']) {
                continue;
            }
            $formFields[$i] = FormField::firstOrCreate(
                ['collection' => $collection, 'form_name' => $form_name, 'field_id' => $field->id],
                ['content' => $field->default ?? null]
            )->append('last_edit');

            if ($field->type == 'block') {
                $formFields[$i]->withRelation($field->id);
            }

            if ($field->type == 'relation') {
                $formFields[$i]->setFormRelation();
            }
            /*
            if($field['type'] == 'image') {
                $formFields[$key]->withRelation($field['id']);
            }
            */
            $i++;
        }

        return eloquentJs(collect($formFields), FormField::class);
    }

    /**
     * Set form.
     *
     * @param string $collection
     * @param string $formName
     * @return void
     */
    protected function setForm(string $collection, string $formName)
    {
        $formFieldInstance = new FormField();
        $formFieldInstance->collection = $collection;
        $formFieldInstance->form_name = $formName;

        $this->formPath = $formFieldInstance->form_fields_path;
        $this->form = FormLoader::load($this->formPath, FormField::class);
    }
}
