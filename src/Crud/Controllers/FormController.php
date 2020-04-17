<?php

namespace Fjord\Crud\Controllers;

use Fjord\TrackEdits\FormEdit;
use Fjord\Crud\Models\FormField;
use Fjord\Fjord\Models\FjordUser;
use Fjord\Crud\Fields\Blocks\Blocks;
use Illuminate\Support\Facades\Route;
use Fjord\Form\Requests\FormReadRequest;
use Fjord\Form\Requests\FormUpdateRequest;

abstract class FormController
{
    use Api\HasBlocks,
        Api\HasRelations;

    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = FormField::class;

    /**
     * Authorize request for operation.
     *
     * @param FjordUser $user
     * @param string $operation
     * @return boolean
     */
    abstract public function authorize(FjordUser $user, string $operation): bool;

    /**
     * Update form_field.
     *
     * @param FormUpdateRequest $request
     * @param int $id
     * @return FormField $formField
     */
    public function update(FormUpdateRequest $request, $id)
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

    /**
     * Show form.
     *
     * @param FormReadRequest $request
     * @return View $view
     */
    public function show(FormReadRequest $request)
    {
        // Getting collection and formName from route.
        $routeSplit = explode('.', Route::currentRouteName());
        array_pop($routeSplit);
        $formName = array_pop($routeSplit);
        $collection = last($routeSplit);

        $config = fjord()->config("form.{$collection}.{$formName}");

        // form_fields entries are loaded or created here.
        $eloquent = $this->initializeFields($config);
        $eloquent['route'] = $config->route_prefix;

        return view('fjord::app')->withComponent('fj-crud-show')
            ->withModels([
                'model' => $eloquent
            ])
            ->withTitle("Form " . $config->names['singular'])
            ->withProps([
                'config' => $config->get('names', 'form', 'previewRoute', 'permissions', 'route_prefix'),
                'headerComponents' => ['fj-crud-show-preview'],
                'controls' => [],
                'content' => ['fj-crud-show-form']
            ]);
    }

    /**
     * Initilaly load or create FormFields.
     *
     * @param [type] $config
     * @return void
     */
    protected function initializeFields($config)
    {
        $fields = [];

        foreach ($config->form->getRegisteredFields() as $field) {
            if (!$field->authorized) {
                continue;
            }

            $formField = FormField::firstOrCreate(
                ['collection' => $config->collection, 'form_name' => $config->formName, 'field_id' => $field->id],
                ['content' => $field->default ?? null]
            )->append('last_edit');

            if ($field instanceof Blocks) {
                $formField->withRelation($field->id);
            }

            $fields[] = $formField;
        }

        return eloquentJs(collect($fields), FormField::class);
    }
}
