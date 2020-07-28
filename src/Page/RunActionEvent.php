<?php

namespace Fjord\Page;

use Fjord\Page\Actions\AttributeBag;
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

        $bindings = array_merge($this->getBindings($request), [
            'attributes' => new AttributeBag($request->all()['attributes'] ?? []),
        ]);

        $result = app()->call([$action, 'run'], $bindings);

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
