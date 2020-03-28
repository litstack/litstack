<?php

namespace AwStudio\Fjord\Fjord\Extend\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait ManagesConfig
{
    protected $configPath;

    protected $configCompiler = [];

    protected function setConfigPath()
    {
        $this->configPath = resource_path(config('fjord.resource_path') . '/');
        $this->configPath .= $this->name != 'aw-studio/fjord'
            ? "packages/{$this->name}/"
            : "";
    }

    public function config($name)
    {
        $path = $this->configPath . str_replace('.', '/', $name) .'.php';

        if(! File::exists($path)) {
            return [];
        }

        $attributes = require $path;

        // Run config compiler if exists.
        foreach($this->configCompiler as $place => $class) {
            if(Str::is($place, $name)) {
                return with(new $class($attributes))->getAttributes();
            }
        }

        return $attributes;
    }

    public function configCompiler($place, $class)
    {
        $this->configCompiler[$place] = $class;
    }
}
