<?php

namespace Ignite\Search;

use Closure;
use Ignite\Config\ConfigFactory;
use Ignite\Config\ConfigHandler;

class SearchConfigFactory extends ConfigFactory
{
    /**
     * Get main.
     *
     * @param  ConfigHandler $config
     * @param  Closure       $method
     * @return array
     */
    public function main(ConfigHandler $config, Closure $method)
    {
        $search = new Search;

        $method($search);

        return $search;
    }
}
