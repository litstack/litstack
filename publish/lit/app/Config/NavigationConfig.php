<?php

namespace LitApp\Config;

use Lit\Application\Navigation\Config;
use Lit\Application\Navigation\Navigation;

class NavigationConfig extends Config
{
    /**
     * Topbar navigation entries.
     *
     * @param \Lit\Application\Navigation\Navigation $nav
     *
     * @return void
     */
    public function topbar(Navigation $nav)
    {
        $nav->section([
            $nav->preset('profile'),
        ]);

        $nav->section([
            $nav->title(__f('fj.user_administration')),

            $nav->preset('user.user', [
                'icon' => fa('users'),
            ]),
            $nav->preset('permissions'),
        ]);

        $nav->section([
            $nav->preset('form.collections.settings', [
                'icon' => fa('cog'),
            ]),
        ]);
    }

    /**
     * Main navigation entries.
     *
     * @param \Lit\Application\Navigation\Navigation $nav
     *
     * @return void
     */
    public function main(Navigation $nav)
    {
        $nav->section([
            $nav->title('Pages'),

            $nav->preset('form.pages.home', [
                'icon' => fa('home'),
            ]),
        ]);
    }
}
