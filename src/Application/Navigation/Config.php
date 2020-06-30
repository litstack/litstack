<?php

namespace Fjord\Application\Navigation;

abstract class Config
{
    /**
     * Build topbar navigation.
     *
     * @param FjordNavigation $nav
     *
     * @return void
     */
    abstract public function topbar(Navigation $nav);

    /**
     * Build main navigation.
     *
     * @param FjordNavigation $nav
     *
     * @return void
     */
    abstract public function main(Navigation $nav);
}
