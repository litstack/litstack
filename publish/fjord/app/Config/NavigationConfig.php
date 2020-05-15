<?php

namespace FjordApp\Config;

use Fjord\Application\Navigation\Config;
use Fjord\Application\Navigation\Navigation;

class NavigationConfig extends Config
{
    /**
     * Topbar navigation entries.
     *
     * @param \Fjord\Application\Navigation\Navigation $nav
     * @return void
     */
    public function topbar(Navigation $nav)
    {
        $nav->section([
            $nav->preset('profile'),
        ]);

        $nav->section([
            $nav->title(__f('fj.user_administration')),

            $nav->preset('users'),
            $nav->preset('permissions')
        ]);

        $nav->section([
            $nav->preset('collections.settings', [
                'title' => __f('fj.settings'),
                'icon' => fa('cog')
            ])
        ]);
    }

    /**
     * Main navigation entries.
     *
     * @param \Fjord\Application\Navigation\Navigation $nav
     * @return void
     */
    public function main(Navigation $nav)
    {
        $nav->section([
            $nav->title('Pages'),

            $nav->group([
                'title' => 'Pages',
                'icon' => '<i class="fas fa-file"></i>',
            ], [
                $nav->preset('pages.home', [
                    'icon' => '<i class="fas fa-home">'
                ]),
            ])
        ]);
    }
}
