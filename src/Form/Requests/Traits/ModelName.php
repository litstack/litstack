<?php

namespace Fjord\Form\Requests\Traits;

use Illuminate\Support\Facades\Request;

trait ModelName
{
    public function model()
    {
        $prefix = config('fjord.route_prefix') . '/';
        $route = str_replace($prefix, '', Request::route()->uri);
        $model = explode('/', $route)[0];

        return $model;
    }
}
