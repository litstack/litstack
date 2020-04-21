<?php

namespace Fjord\Application\Navigation;

use Closure;
use Fjord\Config\ConfigFactory;
use Fjord\Config\ConfigHandler;

class NavigationConfigFactory extends ConfigFactory
{
    /**
     * Resolve query.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure $method
     * @return Navigation
     */
    public function topbar(ConfigHandler $config, Closure $method)
    {
        return $this->nav($method);
    }

    /**
     * Resolve query.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure $method
     * @return Navigation
     */
    public function main(ConfigHandler $config, Closure $method)
    {
        return $this->nav($method);
    }

    /**
     * Create and build new navigation.
     *
     * @param Closure $method
     * @return Navigation
     */
    protected function nav(Closure $method)
    {
        $nav = new Navigation;

        $method($nav);

        return $nav->toArray();
    }
}
