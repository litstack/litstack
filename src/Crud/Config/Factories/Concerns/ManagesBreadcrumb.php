<?php

namespace Ignite\Crud\Config\Factories\Concerns;

use Ignite\Config\ConfigHandler;
use Illuminate\Support\Facades\Route;

trait ManagesBreadcrumb
{
    /**
     * Get the breadcrumb for the given config.
     *
     * @param  ConfigHandler $config
     * @return array
     */
    protected function getBreadcrumb(ConfigHandler $config, $withIndex = true)
    {
        $breadcrumb = [];

        if ($withIndex && $config->has('index')) {
            $breadcrumb = [[
                'title' => $config->names['plural'],
                'url'   => $config->routePrefix(),
            ]];
        }

        if (! $config->has('parent')) {
            return $breadcrumb;
        }

        $parentConfig = $config->parentConfig();

        if (! $parentConfig->has('show')) {
            return array_merge($parentConfig->index->getBreadcrumb(), $breadcrumb);
        }

        return array_merge($parentConfig->show->getBreadcrumb(), $this->parentShowBreadcrumb($parentConfig), $breadcrumb);
    }

    /**
     * Get parent detail view breadcrumb.
     *
     * @param  ConfigHandler $parentConfig
     * @return array
     */
    protected function parentShowBreadcrumb(ConfigHandler $parentConfig): array
    {
        if (! $route = Route::current()) {
            return $route;
        }

        $search = str_replace('.', '_', $parentConfig->getKey());

        foreach ($route->parameters as $parameter => $id) {
            if ($search != $parameter) {
                continue;
            }

            return [[
                'title' => $id,
                'url'   => $parentConfig->routePrefix().'/'.$id,
            ]];
        }

        return [];
    }
}
