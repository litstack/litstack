<?php

namespace AwStudio\Fjord\Fjord\Concerns;

trait ManagesNavigation
{
    public function getNavigation($name = 'main')
    {
        return require fjord_resource_path(config('fjord.navigation_path') . "/{$name}.php");
    }
}
