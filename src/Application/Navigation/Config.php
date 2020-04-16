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
    abstract protected function topbar(FjordNavigation $nav);

    /**
     * Build main navigation.
     *
     * @param FjordNavigation $nav
     * @return void
     */
    abstract protected function main(FjordNavigation $nav);

    /**
     * Prepare topbar params.
     *
     * @return FjordNavigation $nav
     */
    protected function prepareTopbar()
    {
        return new FjordNavigation;
    }

    /**
     * Prepare main params.
     *
     * @return FjordNavigation
     */
    protected function prepareMain()
    {
        return new FjordNavigation;
    }

    /**
     * Resolve topbar.
     *
     * @param FjordNavigation $nav
     * @return array
     */
    protected function resolveTopbar(FjordNavigation $nav)
    {
        return $nav->getEntries();
    }

    /**
     * Resolve main.
     *
     * @param FjordNavigation $nav
     * @return array
     */
    protected function resolveMain(FjordNavigation $nav)
    {
        return $nav->getEntries();
    }
}
