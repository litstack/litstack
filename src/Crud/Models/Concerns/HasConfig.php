<?php

namespace Fjord\Crud\Models\Concerns;

trait HasConfig
{
    /**
     * Load config for model.
     *
     * @return CrudConfig $config
     */
    public function getConfigAttribute()
    {
        return fjord()->config($this->config_type, static::class);
    }

    /**
     * Load config for model.
     *
     * @return CrudConfig $config
     */
    public function scopeConfig($query)
    {
        return self::getConfigAttribute();
    }
}
