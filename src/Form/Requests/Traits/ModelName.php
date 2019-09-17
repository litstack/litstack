<?php

namespace AwStudio\Fjord\Form\Requests\Traits;

use Illuminate\Http\Request;

trait ModelName
{
    public function model()
    {
        $prefix = config('fjord.route_prefix') . '/' ;
        $route = str_replace($prefix, '', $this->route()->uri);
        $model = explode('/', $route)[0];

        return $model;
    }
}
