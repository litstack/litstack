<?php

namespace Fjord\Crud\Controllers\Concerns;

use Illuminate\Support\Facades\Request;

trait ManagesConfig
{
    /**
     * Config instance.
     *
     * @var Instance
     */
    protected $config;

    /**
     * Get config.
     *
     * @return Config
     */
    protected function loadConfig()
    {
        if (!$route = Request::route()) {
            return;
        }

        return $route->getConfig();
    }

    public function getConfig()
    {
        return $this->config;
    }
}
