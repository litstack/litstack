<?php

namespace AwStudio\Fjord\Fjord\Concerns;

use Exception;
use Illuminate\Support\Facades\Schema;
use AwStudio\Fjord\Support\Facades\Form;

trait ManagesForms
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

    public function forms($name)
    {
        $form = config("fjord.{$name}");
        $files = glob(fjord_resource_path("{$name}/*.php"));

        return collect($files)->mapWithKeys(function($path) {
            return [str_replace('.php', '', basename($path)) => $path];
        });
    }

    public function formData($collection = null, $formName = null)
    {
        $form = Form::load($collection, $formName);

        return $form;
    }
}
