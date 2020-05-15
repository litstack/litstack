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
        return fjord()->config($this->getConfigKey(), static::class);
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

    /**
     * Get config key.
     *
     * @return string $key
     */
    public function getConfigKey()
    {
        return $this->configKey ??  Str::snake(last(explode('\\', static::class)));
    }
}
