<?php

namespace Ignite\Application\Navigation;

abstract class Config
{
    /**
     * Build topbar navigation.
     *
     * @param  LitNavigation $nav
     * @return void
     */
    abstract public function topbar(Navigation $nav);

    /**
     * Build main navigation.
     *
     * @param  LitNavigation $nav
     * @return void
     */
    abstract public function main(Navigation $nav);
}
