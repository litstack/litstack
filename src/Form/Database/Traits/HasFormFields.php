<?php

namespace AwStudio\Fjord\Form\Database\Traits;

use AwStudio\Fjord\Support\NestedCollection;
use AwStudio\Fjord\Support\Facades\FormLoader;
use Spatie\MediaLibrary\Models\Media;

trait HasFormFields
{
    public function blocks(string $field_id = '')
    {
        $query = $this->morphMany('AwStudio\Fjord\Form\Database\FormBlock', 'model')
            ->with('translations')
            ->orderBy('order_column');

        if($field_id) {
            $query->where('field_id', $field_id);
        }

        return $query;
    }

    public function formMany($related)
    {
        $instance = $this->newRelatedInstance($related);

        return $this->belongsToMany($related, 'form_relations', 'from_model_id', 'to_model_id', $this->getKeyName(), $instance->getKeyName())
            ->where('form_relations.from_model_type', get_class($this))
            ->where('form_relations.to_model_type', $related)
            ->orderBy('form_relations.order_column');
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
            ? $this->{$form_field->relationship}
            : $this->getBlocks($form_field);

        // setAttribute
        //$this->setAttribute($form_field->relationship, $value);

        // Add eloquentJs relation.
        $this->withRelation($form_field->relationship);

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

        if($form_field->type == 'relation' && $form_field->many) {
            return true;
        }

        return in_array($form_field->id, $fillable);
    }

    public function isTranslatableFormFieldFillable($field)
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

        $form_fields = clone $form->form_fields;

        return $this->getDynamicFieldValues($form_fields);
    }

    public function scopewithFormRelations($query)
    {
        foreach($this->form_fields as $form_field) {
            if($form_field->type != 'relation') {
                continue;
            }

            $query->with($form_field->relationship);
        }

        return $query;
    }
}
