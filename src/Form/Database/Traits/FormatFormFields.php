<?php

namespace Fjord\Form\Database\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;

trait FormatFormFields
{
    /**
     * Get formatted values for the given form_field type.
     *
     * @return void
     */
    public function getFormattedFormFieldValue($form_field, $builder = false, $transform = true)
    {
        $value = $this->getTranslatedFormFieldValue($form_field);

        $value = $this->transformFormFieldValues($form_field, $value, $builder);

        if($form_field->attributeExists('transform') && $transform) {
            return $this->getTransformedFormFieldValueFromConfigCallback($form_field, $value, $builder);
        }

        return $value;
    }

    protected function transformFormFieldValues($form_field, $value, $builder)
    {
        $isJson = ($this->casts['value'] ?? null) == 'json';
        switch($form_field->type ?? null) {
            case 'dt':
            case 'datetime':
                return Carbon::parse($value);
            case 'checkboxes':
                return json_decode($value) ?? [];
            case 'relation':
                return $isJson
                    ? $this->getFormFieldRelation($form_field, $builder)
                    : $this->getFormFieldRelation($builder);
            case 'boolean':
                return (bool) $value;
            case 'select':
                if($form_field->attributeExists('transform_value')) {
                    return call_user_func($form_field->transform_value, $value);
                }
                return $form_field->options[$value] ?? $value;
            case 'block':
                return $this->getBlocks($form_field, $builder);
            case 'image':
                return $form_field->maxFiles > 1
                    ? $this->getMedia($form_field->id)
                    : $this->getMedia($form_field->id)->first();
            case 'morphOne':
                // TODO: 
                return 'morphOne';
            default:
                return $value;
        }
    }

    protected function getTransformedFormFieldValueFromConfigCallback($form_field, $value, $builder = false)
    {
        $transformedValue = call_func($form_field->transform, [$value]);

        return $transformedValue;
    }
}
