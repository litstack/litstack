<?php

namespace AwStudio\Fjord\Application\Vue\Props;

use AwStudio\Fjord\Application\Vue\VueProp;

class ModelProp extends VueProp
{
    protected function handle($value)
    {
        $preparedModels = collect([]);

        foreach($value ?? [] as $title => $model) {
            $preparedModels[$title] = $model->toArray();
        }

        return $preparedModels;
    }
}