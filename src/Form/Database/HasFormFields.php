<?php

namespace AwStudio\Fjord\Form\Database;

use AwStudio\Fjord\Form\FormFieldCollection;
use AwStudio\Fjord\Support\Facades\FormLoader;

trait HasFormFields
{
    public function blocks()
    {
        return $this->morphMany('AwStudio\Fjord\Form\Database\FormBlock', 'model')->orderBy('order_column');
    }

    public function formMany($related)
    {
        $instance = $this->newRelatedInstance($related);

        return $this->belongsToMany($related, 'form_relations', 'from_model_id', 'to_model_id', $this->getKeyName(), $instance->getKeyName())
            ->where('form_relations.from_model_type', get_class($this))
            ->where('form_relations.to_model_type', $related);
    }

    public function getBlocks($form_field, $builder = false)
    {
        if($form_field->type != 'block') {
            return;
        }

        $blockBuilder = $this->blocks()->where('field_id', $form_field->id);

        return $builder ? $blockBuilder : $blockBuilder->get();
    }

    public function setFormRelation($form_field)
    {
        if(! in_array($form_field->type, ['relation', 'block'])) {
            return $this;
        }

        $value = $form_field->type == 'relation'
            ? $form_field->local_key
            : $this->getBlocks($form_field);

        $this->setAttribute($form_field->id, $value);

        $this->withRelation($form_field->id);

        return $this;
    }

    public function setFormRelations()
    {
        foreach($this->form_fields as $form_field) {
            if(! in_array($form_field->type, ['relation', 'block'])) {
                continue;
            }

            $this->setFormRelation($form_field);
        }

        return $this;
    }

    public function formFieldExists($id)
    {
        return $this->findFormField($id) ? true : false;
    }

    public function isFormFieldFillable($form_field)
    {
        $fillable = $this->getFillable();

        if($form_field->type == 'relation') {
            return true;
        }

        return in_array($form_field->id, $fillable);
    }

    public function isTranslatableFormFieldFillable()
    {
        $translationClass = $this->getTranslationModelName();
        $fillable = with(new $translationClass)->getFillable();

        if($field->type == 'relation') {
            return true;
        }

        return in_array($field->id, $fillable);
    }

    /**
     * Returns form_field if $id matches the form_field id.
     *
     * @param  string $id
     * @return AwStudio\Fjord\Form\FormField $form_field
     */
    public function findFormField($id)
    {
        foreach($this->form_fields as $form_field) {
            if($form_field->id == $id) {
                return $form_field;
            }
        }
    }

    public function getFormFieldsPathAttribute()
    {
        return fjord_resource_path("crud/" . $this->getTable() . ".php");
    }

    protected function getDynamicFieldValues($fields)
    {
        foreach($fields as $key => $field) {

            $methodName = "set" . ucfirst($field->id) . "Field";

            if(! method_exists($this, $methodName)) {
                continue;
            }

            call_user_func_array([$this, $methodName], [$fields[$key]]);

         }

        return $fields;
    }

    public function getFormFieldsAttribute()
    {
        $form = FormLoader::load($this->form_fields_path, $this);

        $fields = clone $form->fields;

        return $this->getDynamicFieldValues($fields);
    }

    public function scopewithFormRelations($query)
    {
        foreach($this->form_fields as $form_field) {
            if($form_field->type != 'relation') {
                continue;
            }

            $query->with($form_field->id);
        }

        return $query;
    }

    /**
     * Get formatted values for the given form_field type.
     *
     * @return void
     */
    public function getFormattedFormFieldValue($form_field, $builder = false)
    {
        switch($form_field->type ?? null) {
            case 'relation':
                return $this->{$form_field->local_key};
            case 'boolean':
                return (bool) $this->{$form_field->local_key};
            case 'select':
                return $form_field->options[$this->{$form_field->local_key}];
            case 'block':
                return $this->getBlocks($form_field, $builder);
            default:
                return $this->{$form_field->local_key};
        }
    }

    public function __call($method, $parameters)
    {
        if($form_field = $this->findFormField($method)) {
            return $this->getFormattedFormFieldValue($form_field, true);
        }

        return parent::__call($method, $parameters);
    }
}
