<?php

namespace Fjord\Application\Navigation;

use Fjord\Support\Config as FjordConfig;

abstract class Config extends FjordConfig
{
    /**
     * Build topbar navigation.
     *
     * @param FjordNavigation $nav
     * @return void
     */
    abstract protected function topbar(Navigation $nav);

    /**
     * Build main navigation.
     *
     * @param FjordNavigation $nav
     * @return void
     */
    abstract protected function main(Navigation $nav);
}
