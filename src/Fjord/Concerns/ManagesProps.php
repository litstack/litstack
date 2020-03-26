<?php

namespace AwStudio\Fjord\Fjord\Concerns;

use Illuminate\Support\Facades\Request;

trait ManagesProps
{
    protected $props = [];

    public function getProps($vars)
    {
        extract($vars);

        $this->props = [
            'component' => $component,
            'props' => $this->extendProps($props),
            'models' => collect([]),
            'translatable' => collect([
                'language' => app()->getLocale(),
                'languages' => collect(config('translatable.locales')),
                'fallback_locale' => config('translatable.fallback_locale'),
            ]),
            'config' => collect(config('fjord')),
            'auth' => fjord_user(),
            'permissions' => $this->getPermissions()
        ];

        // Collection to Array for eloquent models
        foreach($models ?? [] as $title => $model) {
            $this->props['models'][$title] = $model->toArray();
        }

        return $this->props;
    }

    protected function getPermissions()
    {
        $permissions = collect([]);
        foreach(auth()->user()->roles ?? [] as $role) {
            $permissions = $permissions->merge(
                $role->permissions->pluck('name')
            );
        }
        return $permissions->unique();
    }

    protected function extendProps($props)
    {
        $props = collect($props ?? []);
        $extension = fjord()->getExtension(Request::route()->getName());

        if(! $extension) {
            return $props;
        }

        return $props->mergeRecursive($extension->getProps());
    }
}
