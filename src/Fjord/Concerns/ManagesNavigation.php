<?php

namespace AwStudio\Fjord\Fjord\Concerns;

trait ManagesNavigation
{
    public function getNavigation($name = 'main')
    {
        $items = require fjord_resource_path(config('fjord.navigation_path') . "/{$name}.php");

        return $this->allowedNavigationItems(collect($items));
    }


    private function allowedNavigationItems($items)
    {
        return $items->map(function ($item) {
            if (is_array($item)) {
                if (array_key_exists('link', $item)) {
                    // if the link does not contain a / ( = is a crud route and not a collection route )
                    // and if the user doesn't have the needed permission,
                    // return null
                    if (!strpos($item['link'], '/') && !fjord_user()->can("read {$item['link']}")) {
                        return;
                    }
                }

                // handle nested items recursively
                return $this->allowedNavigationItems(collect($item));
            }

            // returns strings or components
            return $item;
        })->filter(function ($item) {
            // filters empty == not allowed links
            return $item != null;
        });
    }
}
