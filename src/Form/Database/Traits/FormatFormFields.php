<?php

namespace AwStudio\Fjord\Form\Database\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;

trait FormatFormFields
{
    /**
     * Get formatted values for the given form_field type.
     *
     * @return void
     */
    public function getFormattedFormFieldValue($form_field, $builder = false)
    {
        $value = $this->getTranslatedFormFieldValue($form_field);
        $isJson = ($this->casts['value'] ?? null) == 'json';

        if($form_field->attributeExists('transform')) {
            return $this->getTransformedFormFieldValueFromConfigCallback($form_field, $value, $builder);
        }

        switch($form_field->type ?? null) {
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
