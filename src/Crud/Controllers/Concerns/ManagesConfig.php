<?php

namespace Lit\Crud\Controllers\Concerns;

use Illuminate\Support\Facades\Request;

trait ManagesConfig
{
    /**
     * Config instance.
     *
     * @var \Lit\Config\ConfigHandler
     */
    protected $config;

    /**
     * Get config.
     *
     * @return \Lit\Config\ConfigHandler
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
     * @return \Lit\Config\ConfigHandler
     */
    public function getConfig()
    {
        return $this->config;
    }
}
