<?php

namespace Fjord\Form\Concerns;

use Fjord\Form\FormField;
use Fjord\Support\NestedCollection;
use Illuminate\Support\Facades\Schema;
use Exception;

trait LoadFields
{
    protected $fields = [];

    /**
     * Load fields from file for example from repeatables.
     */
    public function loadFields($path, $model)
    {
        $this->currentPath = $path;

        if (array_key_exists($path, $this->fields)) {
            return $this->fields[$path];
        }

        if (!file_exists($path)) {
            return (object) [];
        }

        $form = $this->getFields(require $path, $model);

        $this->fields[$path] = (object) $form;

        return $this->fields[$path];
    }

    public function getFields($fields, $model, $location = 'fields')
    {

        return $this->getFormFieldObjects(
            $fields,
            function ($field) use ($model, $location) {
                $field->setModel(get_class($model));
                return $this->setFormFieldDefaults($field, $model, $location);
            }
        );
    }

    protected function getFormFieldObjects($fields, $setDefaults)
    {
        $formFields = [];

        foreach ($fields as $key => $field) {
            $authorize = true;
            if (array_key_exists('authorize', $field)) {
                $authorize = $field['authorize'](fjord_user());
            }
            $field['authorize'] = $authorize;
            $formFields[$key] = new FormField($field, $this->currentPath, $setDefaults);
        }

        return new NestedCollection($formFields);
    }

    protected function setFormFieldDefaults($field, $model, $location)
    {
        // Basic Checks.
        $this->isFieldFillable($field, $model);

        if (!$field->attributeExists('translatable')) {

            $field->setAttribute(
                'translatable',
                $this->isFieldTranslatable($field, $model)
            );
        }

        $field->setAttribute('location', $location);

        return $field;
    }

    protected function isFieldFillable($field, $model)
    {
        if (in_array($field->type, [
            'block',
            'image',
            'form_header',

            'hasOne',
            'belongsTo',
            'morphOne',
            'morphTo',

            'hasMany',
            'editHasMany',
            'belongsToMany',
            'morphMany',
            'morphToMany',
            'morphedByMany',
            'relation',
        ])) {
            return;
        }

        if (!$model->isFormFieldFillable($field)) {
            throw new Exception("You may add \"{$field->id}\" to fillables in " . get_class($model) . " to use it as a form field!");
        }

        if (!$field->translatable) {
            return;
        }

        // Check for translation model also
        $translationClass = $model->getTranslationModelName();
        $fillable = with(new $translationClass)->getFillable();

        if (!$model->isTranslatableFormFieldFillable($field)) {
            throw new Exception("You may add \"{$field->id}\" to fillables in " . $translationClass . " to use it as a form field!");
        }
    }

    protected function isFieldTranslatable($field, $model)
    {
        if (!is_translatable($model)) {
            return false;
        }

        return in_array($field->id, $model->translatedAttributes);
    }
}
