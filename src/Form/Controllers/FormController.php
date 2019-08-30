<?php

namespace AwStudio\Fjord\Form\Controllers;

use AwStudio\Fjord\Form\Database\FormField;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class FormController extends Controller
{
    public function update(Request $request, $id)
    {
        $formField = FormField::findOrFail($id);

        $formField->update($request->all());

        return $formField;
    }

    public function show(Request $request)
    {
        [$collection, $form_name] = explode('.', str_replace('fjord.form.', '', Route::currentRouteName()));

        $formFields = $this->getFormFields($collection, $form_name);

        /*
        dd($formFields['data']
            ->where('field_id', 'content_block')
            ->first()
            ->content_block
            ->first()
            ->toArray()
        );
        */

        return view('fjord::vue')->withComponent('form-show')
            ->withModels([
                'formFields' => $formFields
            ])
            ->withTitle(ucfirst($form_name))
            ->withProps([
                'pageName' => $form_name,
            ]);
    }

    protected function getFormFields($collection, $form_name)
    {
        $formFields = [];

        $page = require fjord_resource_path("{$collection}/{$form_name}.php");

        foreach($page['fields'] as $key => $field) {

            $formFields[$key] = FormField::firstOrCreate(
                ['collection' => $collection, 'form_name' => $form_name, 'field_id' => $field['id']],
                ['content' => $field['default'] ?? null]
            );

            if($field['type'] == 'block') {
                $formFields[$key]->withRelation($field['id']);
            }

            if($field['type'] == 'relation') {
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
}
