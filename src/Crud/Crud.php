<?php

namespace Fjord\Crud;

use Fjord\Support\Facades\Config;

/**
 * Crud Singleton.
 * 
 * @see \Fjord\Support\Facades\Crud
 */
class Crud
{
    /**
     * Get config by model.
     *
     * @param string $model
     * @return void
     */
    public function config($model)
    {
        $configKey = "crud." . lcfirst(class_basename($model));

        if (Config::exists($configKey)) {
            return Config::get($configKey);
        }
    }
}
