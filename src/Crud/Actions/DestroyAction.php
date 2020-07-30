<?php

namespace Fjord\Crud\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class DestroyAction
{
    public function run(Collection $models)
    {
        $models->map(fn ($item) => $item->delete());

        return $this->resolveResponse($models);
    }

    protected function resolveResponse($models)
    {
        $route = Route::matchesUri(
            request()->headers->get('referer'), 'GET'
        );

        if (! $route) {
            return $this->successMessage($models);
        }

        if (! Str::endsWith($route->getName(), '.show')) {
            return $this->successMessage($models);
        }

        return redirect(
            route(Str::replaceLast('show', 'index', $route->getName()))
        );
    }

    protected function successMessage($models)
    {
        return success(__f_choice('messages.deleted_items', count($models)));
    }
}
