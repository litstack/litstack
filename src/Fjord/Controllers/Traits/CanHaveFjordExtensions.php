<?php

namespace AwStudio\Fjord\Fjord\Controllers\Traits;

use Illuminate\Support\Str;

trait CanHaveFjordExtensions
{
    protected function getExtensions(string $place)
    {
        $methods = get_class_methods($this);

        $components = collect([]);
        foreach($methods as $method) {
            if(! Str::startsWith($method , 'add') || ! Str::endsWith($method, 'Extension')) {
                continue;
            }

            $components = $components->mergeRecursive(
                call_user_func_array([$this, $method], [])
            );
        }

        return $components[$place] ?? [];
    }
}
