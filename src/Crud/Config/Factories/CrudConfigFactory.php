<?php

namespace Ignite\Crud\Config\Factories;

use Closure;
use Ignite\Config\ConfigFactory;
use Ignite\Config\ConfigHandler;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

class CrudConfigFactory extends ConfigFactory
{
    /**
     * Get names.
     *
     * @param  ConfigHandler $config
     * @param  Closure       $method
     * @return array
     */
    public function names(ConfigHandler $config, Closure $method)
    {
        return $method($config->getModelInstance());
    }

    /**
     * Make route prefix.
     *
     * @param  \Ignite\Config\ConfigHandler $config
     * @param  Closure                      $method
     * @return string
     */
    public function routePrefix(ConfigHandler $config, Closure $method)
    {
        $prefix = '';

        if ($config->has('parent')) {
            $parentConfig = $config->parentConfig;
            $attribute = str_replace('.', '_', $parentConfig->getKey());

            $prefix .= "{$parentConfig->route_prefix}/{{$attribute}}/";
        }

        $prefix .= $method();

        if ($route = RouteFacade::current()) {
            $this->fillParametersToRoutePrefix($prefix, $route);
        }

        return strip_slashes($prefix);
    }

    /**
     * Fill current parameter values to route prefix.
     *
     * @param  $prefix
     * @param  Route $route
     * @return void
     */
    protected function fillParametersToRoutePrefix(&$prefix, Route $route)
    {
        foreach ($route->parameters as $parameter => $value) {
            $prefix = str_replace("{{$parameter}}", $value, $prefix);
        }
    }
}
