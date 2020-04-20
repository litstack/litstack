<?php

namespace Fjord\Crud\Config\Factories;

use Closure;
use Fjord\Vue\Crud\CrudTable;
use Fjord\Config\ConfigFactory;
use Fjord\Config\ConfigHandler;

class CrudIndexConfigFactory extends ConfigFactory
{
    /**
     * Resolve index query.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure $method
     * @return Builder
     */
    public function indexQuery(ConfigHandler $config, Closure $method)
    {
        $indexQuery = clone $config->query;

        $method($indexQuery);

        return $indexQuery;
    }

    /**
     * Setup index table.
     *
     * @param \Fjord\Config\Types\CrudConfig $config
     * @param Closure $method
     * @return \Fjord\Vue\Crud\CrudTable
     */
    public function index(ConfigHandler $config, Closure $method)
    {
        $table = new CrudTable($config);

        $method($table);

        return $table;
    }
}
