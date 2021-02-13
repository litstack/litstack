<?php

namespace Ignite\Crud\Config\Factories\Concerns;

use Ignite\Config\ConfigHandler;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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
        if (! is_null($model = $parentConfig->getModelInstance())) {
            $title = $parentConfig->names['singular'];
            if ($title == Str::singular(class_basename($parentConfig->model))) {
                $title = $model->id;
            }

            return [[
                'title' => strlen($title) > 15 ? substr($title, 0, 15).'...' : $title,
                'url'   => $parentConfig->routePrefix().'/'.$model->id,
            ]];
        }

        $search = str_replace('.', '_', $parentConfig->getKey());

        foreach ($route->parameters as $parameter => $id) {
            if ($search != $parameter) {
                continue;
            }
            $breadcrumb = ! is_null($parentConfig->breadcrumb) ? strip_tags($parentConfig->model::findOrFail($id)[$parentConfig->breadcrumb]) : null;

            return [[
                'title'      => $id,
                'url'        => $parentConfig->routePrefix().'/'.$id,
                'breadcrumb' => $breadcrumb,
            ]];
        }

        return [];
    }
}
