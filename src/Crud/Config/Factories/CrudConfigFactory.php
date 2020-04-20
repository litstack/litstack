<?php

namespace Fjord\Crud\Config\Factories;

use Closure;
use Fjord\Config\ConfigFactory;
use Fjord\Config\ConfigHandler;

class CrudConfigFactory extends ConfigFactory
{
    /**
     * Resolve query.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure $method
     * @return Builder
     */
    public function query(ConfigHandler $config, Closure $method)
    {
        $query = $config->model::query();

        $method($query);

        return $query;
    }
}
