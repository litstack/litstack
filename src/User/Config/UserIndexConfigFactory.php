<?php

namespace Fjord\User\Config;

use Closure;
use Fjord\Vue\Table;
use Fjord\Config\ConfigFactory;
use Fjord\Config\ConfigHandler;

class UserIndexConfigFactory extends ConfigFactory
{
    /**
     * Setup fj-users component.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure $method
     * @return \Fjord\Vue\Component
     */
    public function component(ConfigHandler $config, Closure $method)
    {
        $component = component('fj-users');

        $deleteAll = component('fj-index-delete-all')
            ->prop('route_prefix', $config->routePrefix);

        $component->slot('indexControls', $deleteAll);

        $method($component);

        return $component;
    }
}
