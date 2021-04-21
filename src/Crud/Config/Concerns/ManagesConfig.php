<?php

namespace Ignite\Crud\Config\Concerns;

use Ignite\Support\Facades\Config;

trait ManagesConfig
{
    /**
     * Get config handler.
     *
     * @return ConfigHandler
     */
    public function get()
    {
        return Config::get(static::class);
    }
}
