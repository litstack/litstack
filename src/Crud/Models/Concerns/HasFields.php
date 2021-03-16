<?php

namespace Ignite\Crud\Models\Concerns;

use Ignite\Crud\Field;
use Ignite\Crud\Fields\Media\MediaField;
use Ignite\Crud\FormResource;
use Ignite\Crud\Models\Media;
use Ignite\Crud\RelationField;
use Illuminate\Support\Collection;

trait HasFields
{
    /**
     * Get fields from config.
     *
     * @return Collection
     */
    public function getFieldsAttribute(): Collection
    {
        if (! $form = $this->getForm()) {
            return collect([]);
        }

        return $form->getRegisteredFields() ?? collect([]);
    }

    /**
     * Get form.
     *
     * @return BaseForm|null
     */
    public function getForm()
    {
        if (! $config = $this->config) {
            return;
        }

        return $config->{$this->getFormType()};
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
     * Determines if field with id exists.
     *
     * @param  string $id
     * @return bool
     */
    public function fieldExists(string $id)
    {
        return $this->findField($id) ? true : false;
    }

    /**
     * Find field by id.
     *
     * @param  string             $id
     * @return \Ignite\Crud\Field
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
     * @param  Field       $field
     * @param  string|null $locale
     * @return mixed
     */
    public function getFormattedFieldValue(Field $field, $locale = null)
    {
        return $field->castValue(
            $this->getFieldValue($field, $locale)
        );
    }

    /**
     * Gets field value.
     *
     * @param  Field       $field
     * @param  string|null $locale
     * @return mixed
     */
    public function getFieldValue(Field $field, $locale = null)
    {
        if ($field instanceof MediaField) {
            return $this->getMediaFieldValue($field);
        }

        if ($field instanceof RelationField) {
            return $this->getRelationValue($field->id);
        }

        if ($field->translatable) {
            return $this->getTranslatedFieldValue($field, $locale ?: app()->getLocale());
        }

        return $this->value[$field->local_key] ?? null;
    }

    /**
     * Get value for relation field.
     *
     * @param  RelationField $field
     * @return mixed
     */
    public function getRelationFieldValue(RelationField $field)
    {
        return $this->getRelationValue($field->id);
    }

    /**
     * Get value for media field.
     *
     * @param  MediaField       $field
     * @return Collection|Media
     */
    public function getMediaFieldValue(MediaField $field)
    {
        $media = $this->getMedia($field->id);
        if ($field->maxFiles == 1) {
            $media = $media->first();
        }

        return $media;
    }

    /**
     * Get translated field value.
     *
     * @param  Field  $field
     * @param  string $locale
     * @return mixed
     */
    public function getTranslatedFieldValue(Field $field, string $locale)
    {
        $value = $this->translation[$locale] ?? [];

        return $value[$field->local_key] ?? null;
    }

    /**
     * Return the resource instance of this model.
     *
     * @return FormResource
     */
    public function resource(): FormResource
    {
        $class = $this->getResourceClass();

        return new $class($this);
    }

    /**
     * Get JsonResource class name.
     *
     * @return string
     */
    protected function getResourceClass()
    {
        if (property_exists($this, 'resource')) {
            return $this->resource;
        }

        return FormResource::class;
    }
}
