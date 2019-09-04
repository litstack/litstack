<?php

namespace AwStudio\Fjord\Form\Database\Traits;

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

        switch($form_field->type ?? null) {
            case 'relation':
                return $isJson
                    ? $this->getFormFieldRelation($form_field, $builder)
                    : $this->getFormFieldRelation($builder);
            case 'boolean':
                return (bool) $value;
            case 'select':
                return $form_field->options[$value];
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
}
