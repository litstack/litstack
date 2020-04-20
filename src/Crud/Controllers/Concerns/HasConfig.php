<?php

namespace Fjord\Crud\Controllers\Concerns;

use Illuminate\Support\Facades\Request;

trait HasConfig
{
    /**
     * Config instance.
     *
     * @var Instance
     */
    protected $config;

    /**
     * Get query builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->config->query;
    }

    /**
     * Get config.
     *
     * @return Config
     */
    public function loadConfig()
    {
        return Request::route()->getConfig();
    }
}
