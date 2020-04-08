<?php

namespace Fjord\Form\Requests\Traits;

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
    public function authorizeController(Request $request, string $operation): bool
    {
        $controller = $request->route()->controller;

        return with(new $controller)
            ->authorize(fjord_user(), $operation);
    }
}
