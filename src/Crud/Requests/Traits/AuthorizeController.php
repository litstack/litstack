<?php

namespace Ignite\Crud\Requests\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait AuthorizeController
{
    /**
     * Check authorize method in controller.
     *
     * @param  Request $request
     * @param  string  $operation
     * @return bool
     */
    public function authorizeController(Request $request, string $operation, $controller = null): bool
    {
        if (! lit_user()) {
            return false;
        }

        if (! $controller) {
            $controller = $this->getCrudController($request);
        }

        $reflection = new ReflectionClass($controller);
        $params = $reflection->getMethod('authorize')->getParameters();

        if (count($params) == 2) {
            return with(new $controller())
                ->authorize(lit_user(), $operation);
        }

        $modelId = null;
        $config = $this->getCurrentCrudConfigFromRequest($request);

        if ($config->controller == $controller &&
            $model = $config->getModelInstance()) {
            $modelId = $model->getKey();
        }

        return with(new $controller())
            ->authorize(lit_user(), $operation, $modelId);
    }

    /**
     * Get CRUD controller for the given request.
     *
     * @param  Request $request
     * @return string
     */
    protected function getCrudController(Request $request)
    {
        if ($config = $request->route()->getConfig()) {
            return $config->controller;
        }

        return $request->route()->controller;
    }

    protected function getCurrentCrudConfigFromRequest(Request $request)
    {
        try {
            $route = Route::getRoutes()->match($request);
        } catch (HttpException $e) {
            return;
        }

        return $route->getConfig();
    }
}
