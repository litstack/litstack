<?php

namespace Fjord\Crud\Controllers\Concerns;

use Illuminate\Support\Facades\Request;

trait ManagesConfig
{
    /**
     * Config instance.
     *
     * @var \Fjord\Config\ConfigHandler
     */
    protected $config;

    /**
     * Get config.
     *
     * @return \Fjord\Config\ConfigHandler
     */
    protected function loadConfig()
    {
        if (! $route = Request::route()) {
            return;
        }

        return $route->getConfig();
    }

    /**
     * Get crud/form config.
     *
     * @return \Fjord\Config\ConfigHandler
     */
    public function getConfig()
    {
        return $this->config;
    }
}
