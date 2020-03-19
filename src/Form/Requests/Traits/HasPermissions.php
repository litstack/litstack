<?php

namespace AwStudio\Fjord\Form\Requests\Traits;

use Illuminate\Http\Request;

trait HasPermissions
{
    public function hasPermissions(Request $request)
    {
        $class_name = $request->route()->controller;

        $class_reflex = new \ReflectionClass($class_name);
        $class_constants = $class_reflex->getConstants();

        if (!array_key_exists('PERMISSIONS', $class_constants)) {
            return false;
        }
        return $class_name::PERMISSIONS;
    }
}
