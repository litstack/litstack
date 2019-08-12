<?php

namespace AwStudio\Fjord\Fjord\Concerns;

trait ManagesNavigation
{
    public function getNavigation()
    {
        return require fjord_resource_path('navigation.php');
    }
}
