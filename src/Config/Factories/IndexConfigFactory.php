<?php

namespace Fjord\Config\Factories;

use Closure;
use Fjord\Vue\Table;
use Fjord\Config\ConfigFactory;
use Fjord\Config\ConfigHandler;

class IndexConfigFactory extends ConfigFactory
{
    /**
     * Get filter.
     *
     * @param ConfigHandler $config
     * @param Closure $closure
     * @return void
     */
    public function filter(ConfigHandler $config, Closure $closure)
    {
        return (object) $closure();
    }

    /**
     * Handle index.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param \Closure $closure
     * @return \Fjord\Vue\Table
     */
    public function index(ConfigHandler $config, Closure $closure)
    {
        $table = new Table($config);

        $closure($table);

        return $table;
    }
    /**
     * Resolve index query.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param \Closure $closure
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function indexQuery(ConfigHandler $config, Closure $closure)
    {
        $indexQuery = $config->model::query();

        $closure($indexQuery);

        return $indexQuery;
    }
}
