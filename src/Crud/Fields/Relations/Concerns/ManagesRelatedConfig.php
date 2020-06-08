<?php

namespace Fjord\Crud\Fields\Relations\Concerns;

use InvalidArgumentException;
use Fjord\Support\Facades\Crud;

trait ManagesRelatedConfig
{
    /**
     * Related Crud config.
     *
     * @var \Fjord\Config\ConfigHandler
     */
    protected $relatedConfig;

    /**
     * Load related Crud config.
     *
     * @param string $related
     * @return void
     * 
     * @throws \InvalidArgumentException
     */
    public function loadRelatedConfig(string $related)
    {
        $this->relatedConfig = Crud::config($related);

        if (!$this->relatedConfig) {
            throw new InvalidArgumentException("Missing Crud config for Model {$related}.");
        }

        $config = $this->relatedConfig->get(
            'names',
            'search',
            'route_prefix'
        );

        $this->setAttribute('config', $config);

        if (!$this->preview) {
            $this->preview = $this->relatedConfig->index->getTable()->getTable();
        }
    }

    /**
     * Get related Crud config.
     *
     * @return \Fjord\Config\ConfigHandler
     */
    public function getRelatedConfig()
    {
        return $this->relatedConfig;
    }
}
