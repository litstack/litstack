<?php

namespace Fjord\Crud\Models\Concerns;

use Fjord\Crud\RelationField;

trait HasFields
{
    /**
     * Get fields from config.
     *
     * @return Field
     */
    public function getFieldsAttribute()
    {
        return $this->getForm()->getRegisteredFields();
    }

    /**
     * Get form.
     *
     * @return BaseForm
     */
    public function getForm()
    {
        return $this->config->{$this->getFormType()};
    }

    /**
     * Get form type.
     *
     * @return string
     */
    protected function getFormType()
    {
        return $this->form_type ?: 'show';
    }

    /**
     * Check if field with id exists.
     *
     * @param string $id
     * @return boolean
     */
    public function fieldExists(string $id)
    {
        return $this->findField($id) ? true : false;
    }

    /**
     * Find field by id.
     *
     * @param  string $id
     * @return \Fjord\Crud\Field
     */
    public function findField($id)
    {
        foreach ($this->fields as $field) {
            if ($field->id == $id) {
                return $field;
            }
        }
    }

    /**
     * Get formatted values for the given form_field type.
     *
     * @return void
     */
    public function getFormattedFieldValue($field,  $locale = null)
    {
        return $field->cast(
            $this->getFieldValue($field, $locale)
        );
    }

    /**
     * Get field value.
     *
     * @param Field $field
     * @return mixed
     */
    public function getFieldValue($field)
    {
        if ($field instanceof RelationField) {
            return $this->{$field->id};
        }

        if (!is_translatable(static::class)) {
            return $this->{$field->id};
        }

        if ($field->translatable) {
            $value = $this->translation[app()->getLocale()] ?? [];
        } else {
            $value = $this->translation[config('translatable.fallback_locale')] ?? [];
        }

        return $value['value'] ?? null;
    }
}
