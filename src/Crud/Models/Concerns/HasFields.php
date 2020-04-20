<?php

namespace Fjord\Crud\Models\Concerns;

use Fjord\Crud\Models\FormBlock;

trait HasFields
{
    /**
     * Initilizing blocks relation.
     *
     * @param string $field_id
     * @return void
     */
    public function blocks(string $field_id = '')
    {
        $query = $this->morphMany(FormBlock::class, 'model')
            ->with('translations')
            ->orderBy('order_column');

        if ($field_id) {
            $query->where('field_id', $field_id);
        }

        return $query;
    }

    /**
     * Form manyRelation.
     *
     * @param string $related
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function manyRelation($related)
    {
        $instance = $this->newRelatedInstance($related);

        return $this->belongsToMany($related, 'form_relations', 'from_model_id', 'to_model_id', $this->getKeyName(), $instance->getKeyName())
            ->where('form_relations.from_model_type', get_class($this))
            ->where('form_relations.to_model_type', $related)
            ->orderBy('form_relations.order_column');
    }

    /**
     * Form oneRelation.
     *
     * @param string $related
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /*
    public function oneRelation($related)
    {
        $instance = $this->newRelatedInstance($related);

        return $this->belongsToMany($related, 'form_relations', 'from_model_id', 'to_model_id', $this->getKeyName(), $instance->getKeyName())
            ->where('form_relations.from_model_type', get_class($this))
            ->where('form_relations.to_model_type', $related)
            ->orderBy('form_relations.order_column');
    }
    */

    /**
     * Get fields from config.
     *
     * @return Field
     */
    public function getFieldsAttribute()
    {
        return $this->config->form->getRegisteredFields();
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
    public function getFormattedFieldValue($field)
    {
        return $field->cast(
            $this->getFieldValue($field)
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
        if ($field->isRelation()) {
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

    /**
     * Call set{id}Field methods to get dymanic values.
     *
     * @param Field $fields
     * @return mixed
     */
    protected function getDynamicFieldValues($fields)
    {
        foreach ($fields as $key => $field) {
            $methodName = "set" . ucfirst($field->id) . "Field";

            if (!method_exists($this, $methodName)) {
                continue;
            }

            call_user_func_array([$this, $methodName], [$field]);
        }

        return $fields;
    }
}
