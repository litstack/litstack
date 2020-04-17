<?php

namespace Fjord\Crud\Controllers\Concerns;

trait HasConfig
{
    /**
     * Get query builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->config()->query;
    }

    /**
     * Get config.
     *
     * @return Config
     */
    public function config()
    {
        return $this->model::config();
    }
}
