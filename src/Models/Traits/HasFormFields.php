<?php

namespace AwStudio\Fjord\Models\Traits;

trait HasFormFields
{
    protected $formRelations;

    public function fjordMany($related)
    {
        $instance = $this->newRelatedInstance($related);

        return $this->belongsToMany($related, 'relations', 'from_model_id', 'to_model_id', $this->getKeyName(), $instance->getKeyName())
            ->where('relations.from_model_type', get_class($this))
            ->where('relations.to_model_type', $related);
    }

    public function withFields()
    {
        $this->append('fields');

        return $this;
    }

    public function findField($id)
    {
        foreach($this->fields as $field) {
            if($field['id'] == $id) {
                return $field;
            }
        }
    }

    protected function getDynamicFieldValues($fields)
    {

        // Get dynamic field for model.
        foreach($fields as $key => $field) {
            $methodName = "set" . ucfirst($field->id) . "Field";
            
            if(! method_exists($this, $methodName)) {
                continue;
            }

            call_user_func_array([$this, $methodName], [$fields[$key]]);
        }

        return $fields;
    }

    public function getFieldsAttribute()
    {
        $crud = fjord()->getCrud($this->getTable()) ?? [];

        if(! array_key_exists('fields', $crud)) {
            return [];
        }

        $fields = clone $crud['fields'];

        return $this->getDynamicFieldValues($fields);
    }

    public function scopewithFormRelations($query)
    {
        foreach($this->fields as $field) {
            if($field->type != 'relation') {
                continue;
            }

            $query->with($field->id);
        }

        return $query;
    }

    public function formRelation($field)
    {
        return;
        //return $this->hasMany('')
        //dd($this, $field);
    }
}
