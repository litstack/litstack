<?php

namespace Fjord\Page;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RunActionEvent
{
    /**
     * Handle RunActionEvent.
     *
     * @param  Request $request
     * @return mixed
     */
    public function handle(Request $request)
    {
        if (! class_exists($request->action)) {
            abort(404, debug("Couldn't find action [{$request->action}]."));
        }

        $action = app()->make($request->action);

        $result = app()->call([$action, 'run'], $this->getBindings($request));

        if (! $result instanceof JsonResponse) {
            return $this->defaultResponse();
        }

        return $result;
    }

    /**
     * Get default response.
     *
     * @return JsonResponse
     */
    protected function defaultResponse()
    {
        return new JsonResponse([
            'message' => 'Action executed.',
        ]);
    }

    /**
     * Get action bindings.
     *
     * @return array
     */
    protected function getBindings(Request $request)
    {
        return [];
    }
}
