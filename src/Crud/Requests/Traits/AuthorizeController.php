<?php

namespace Fjord\Crud\Requests\Traits;

use ReflectionClass;
use Illuminate\Http\Request;

trait AuthorizeController
{
    /**
     * Check authorize method in controller.
     *
     * @param Request $request
     * @param string $operation
     * @return bool
     */
    public function authorizeController(Request $request, string $operation, $controller = null): bool
    {
        if (!fjord_user()) {
            return false;
        }

        if (!$controller) {
            $controller = $request->route()->controller;
        }

        $reflection = new ReflectionClass($controller);
        $params = $reflection->getMethod('authorize')->getParameters();

        if (count($params) == 2) {
            return with(new $controller)
                ->authorize(fjord_user(), $operation);
        }
        return with(new $controller)
            ->authorize(fjord_user(), $operation, $request->id);
    }
}
