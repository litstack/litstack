<?php

namespace Lit\Application\Navigation;

use Closure;
use Lit\Config\ConfigFactory;
use Lit\Config\ConfigHandler;

class NavigationConfigFactory extends ConfigFactory
{
    /**
     * Resolve query.
     *
     * @param \Lit\Config\ConfigHandler $config
     * @param Closure                     $method
     *
     * @return Navigation
     */
    public function topbar(ConfigHandler $config, Closure $method)
    {
        return $this->nav($method);
    }

    /**
     * Resolve query.
     *
     * @param \Lit\Config\ConfigHandler $config
     * @param Closure                     $method
     *
     * @return Navigation
     */
    public function main(ConfigHandler $config, Closure $method)
    {
        return $this->nav($method);
    }

    /**
     * Create and build new navigation.
     *
     * @param Closure $method
     *
     * @return Navigation
     */
    protected function nav(Closure $method)
    {
        $nav = new Navigation();

        $method($nav);

        $nav = $nav->toArray();

        // Unset empty entries.
        foreach ($nav as $i => $section) {
            foreach ($section as $key => $entry) {
                if (! $entry) {
                    unset($nav[$i][$key]);
                } elseif ($entry['type'] == 'group') {
                    foreach ($entry['children'] as $ci => $child) {
                        if (! $child) {
                            unset($nav[$i][$key]['children'][$ci]);
                        }
                    }
                    if (empty($nav[$i][$key]['children'])) {
                        unset($nav[$i][$key]);
                    }
                }
            }
            if ($this->isSectionEmpty($nav[$i])) {
                unset($nav[$i]);
            }
        }

        return $nav;
    }

    /**
     * Is section emtpy. True when it only has titles.
     *
     * @param array $section
     *
     * @return bool
     */
    protected function isSectionEmpty($section)
    {
        if (empty($section)) {
            return;
        }

        foreach ($section as $entry) {
            if ($entry['type'] != 'title') {
                return false;
            }
        }

        return true;
    }
}
