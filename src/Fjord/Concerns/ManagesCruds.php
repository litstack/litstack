<?php

namespace AwStudio\Fjord\Fjord\Concerns;

use Exception;
use Illuminate\Support\Facades\Schema;

trait ManagesCruds
{
    protected $cruds = [];

    public function cruds()
    {
        return collect($this->crudFiles())->map(function($path) {
            return str_replace('.php', '', basename($path));
        });
    }

    public function crudFiles()
    {
        return glob(fjord_resource_path("crud/*.php"));
    }

    public function crudFilePath($title)
    {
        return fjord_resource_path("crud/{$title}.php");
    }

    public function getCrud($title)
    {
        if(array_key_exists($title, $this->cruds)) {
            return $this->cruds[$title];
        }

        return $this->loadCrud($title);
    }

    protected function loadCrud($title)
    {
        if(! file_exists($this->crudFilePath($title))) {
            return [];
        }

        $crud = require $this->crudFilePath($title);

        // Prepare Fields.
        if(array_key_exists('fields', $crud)) {
            $crud['fields'] = $this->prepareFields(
                $crud['fields'],
                $this->crudFilePath($title),
                function($field) use ($crud) {
                    return $this->prepareCrudField($field, $crud);
                }
            );
        }

        $this->cruds[$title] = $crud;

        return $this->cruds[$title];
    }

    protected function prepareCrudField($field, $crud)
    {

        if(! $field->attributeExists('translatable')) {
            $field->setAttribute(
                'translatable',
                $this->isFieldTranslatable($field, $crud['model'])
            );
        }

        $this->isFieldFillable($field, $crud['model']);

        return $field;
    }

    protected function isFieldFillable($field, $model)
    {
        if($field->type == 'block') {
            return;
        }

        if($field->type == 'image') {
            return;
        }

        $modelInstance = with(new $model);
        $fillable = $modelInstance->getFillable();

        if(! in_array($field->id, $fillable) && $field->type != 'relation') {
            throw new Exception("You may add \"{$field['id']}\" to fillables in " . $model . " to use it as a form field!");
        }

        if(! $field->translatable) {
            return;
        }

        // Check for translation model also
        $translationModel = $modelInstance->getTranslationModelName();
        $fillable = with(new $translationModel)->getFillable();

        if(! in_array($field->id, $fillable)) {
            throw new Exception("You may add \"{$field['id']}\" to fillables in " . $translationClass . " to use it as a form field!");
        }
    }

    protected function isFieldTranslatable($field, $model)
    {
        if(! is_translateable($model)) {
            return false;
        }

        $translationModel = with(new $model)->getTranslationModelName();
        $translationTableName = with(new $translationModel)->getTable();
        $tableCols = Schema::getColumnListing($translationTableName);

        return in_array($field->id ,$tableCols);
    }


}
