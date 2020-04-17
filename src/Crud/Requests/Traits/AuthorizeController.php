<?php

namespace Fjord\Crud\Requests\Traits;

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
        if (!$controller) {
            $controller = $request->route()->controller;
        }

        return with(new $controller)
            ->authorize(fjord_user(), $operation);
    }
}
