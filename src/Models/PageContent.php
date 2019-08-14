<?php

namespace AwStudio\Fjord\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class PageContent extends Model implements TranslatableContract
{
    use Translatable, Traits\CanEloquentJs, Traits\HasFormFields;

    protected $table = 'page_content';
    protected $translationModel = Translations\PageContentTranslation::class;

    public $fillable = ['page_name', 'field_name', 'content'];
    public $translatedAttributes = ['content'];
    protected $appends = ['translation', 'fields'];

    public function toArray()
    {
        $array = parent::toArray();
        if($this->field->type == 'boolean') {
            $array['content'] = $this->getFormContent();
        }
        return $array;
    }

    public function update(array $attributes = [], array $options = [])
    {
        if (! $this->field->translatable) {
            $attributes[config('translatable.locales')[0]]['content'] = $attributes['content'];
        }

        return parent::update($attributes, $options);
    }

    public function __call($key, $parameters)
    {
        if (($this->attributes['field_name'] ?? null) == $key
            && $this->field->type == 'relation') {
            return $this->getFormRelation(true);
        }

        return parent::__call($key, $parameters);
    }

    public function getTranslationAttribute()
    {
        return $this->getTranslationsArray();
    }

    public function form_relations()
    {
        return $this->fjordMany($this->field->model ?? '');
    }

    public function form_relation()
    {
        return $this->hasOne($this->field->model, 'id', 'content');
    }

    public function setFormRelation()
    {
        $this->setAttribute($this->field_name, $this->{$this->field_name});

        return $this;
    }

    public function getFormRelation($getQuery = false)
    {
        if ($this->field->many) {
            return $getQuery
                ? $this->form_relations()
                : $this->form_relations;
        }

        return $getQuery
            ? $this->form_relation()
            : $this->form_relation;
    }

    public function getFormContent()
    {
        switch($this->field->type ?? null) {
            case 'relation':
                return $this->getFormRelation();
            case 'boolean':
                return (bool) $this->content;
            case 'select':
                return $this->field->options[$this->content];
            default:
                return $this->content;
        }
    }

    public function getTranslatedFormContent()
    {
        if ($this->field->translatable) {
            $attributes = $this->getTranslationsArray()[app()->getLocale()] ?? $this->attributes;
            return $attributes['content'];
        } else {
            return $this->getTranslationsArray()[config('translatable.locales')[0]]['content'];
        }
    }

    public function getAttribute($key)
    {
        if (($this->attributes['field_name'] ?? null) == $key) {
            return $this->getFormContent();
        }

        if ($key == 'content') {
            return $this->getTranslatedFormContent();
        }

        return parent::getAttribute($key);
    }

    public function setTranslatableContent($value)
    {
        if ($this->field->translatable ?? false) {
            $this->translate(app()->getLocale())->content = $value;
        } else {
            $this->translate(config('translatable.locales')[0])->content = $value;
        }
    }

    public function setAttribute($key, $value)
    {
        if ($this->field->type ?? null == 'relation') {
            if (($this->field->local_key ?? null) == $key) {
                return $this->setAttribute('content', $value);
            }
        }

        if ($key == 'content') {
            return $this->setTranslatableContent($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function getFieldAttribute()
    {
        return $this->fields[0] ?? null;
    }

    public function getFieldsAttribute()
    {
        if (! array_key_exists('field_name', $this->attributes)) {
            return form_collect([]);
        }

        $page = fjord()->getPage($this->page_name ?? null) ?? [];

        if (! array_key_exists('fields', $page)) {
            return form_collect([]);
        }

        $fields = $page['fields'];

        $query = $fields->where('id', $this->attributes['field_name']);

        if(! $query->first()) {
            return form_collect([]);
        }

        $field = clone $query->first();

        if (! $field) {
            return form_collect([]);
        }

        $fields = $this->getDynamicFieldValues(form_collect([$field]));

        if ($field->type != 'relation') {
            $field->id = 'content';
        }

        return form_collect([$field]);
    }
}
