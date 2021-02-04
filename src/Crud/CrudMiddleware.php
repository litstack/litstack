<?php

namespace Ignite\Crud;

use Closure;
use Ignite\Crud\Config\CrudConfig;
use Illuminate\Http\Request;

class CrudMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->bindModelInstance($request);

        return $next($request);
    }

    /**
     * Bin model instance to the associated config.
     *
     * @param  Request $request
     * @return void
     */
    protected function bindModelInstance(Request $request)
    {
        if (! $route = $request->route()) {
            return;
        }

        if (! $config = $route->getConfig()) {
            return;
        }

        if (! $config->is(CrudConfig::class)) {
            return;
        }

        $config->setModelInstanceFromCurrentRoute();
    }
}
