<?php

namespace Fjord\Crud\Controllers\Concerns;

use Illuminate\Support\Facades\Request;
use Fjord\Crud\Models\Traits\TrackEdits;

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
        $query = $this->config->query;

        if (in_array(TrackEdits::class, class_uses(new $this->model))) {
            $query->with('last_edit');
        }

        return $query;
    }

    /**
     * Get config.
     *
     * @return Config
     */
    public function loadConfig()
    {
        if (!$route = Request::route()) {
            return;
        }

        return $route->getConfig();
    }
}
