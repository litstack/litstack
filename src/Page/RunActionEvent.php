<?php

namespace Ignite\Page;

use Ignite\Support\AttributeBag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

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
            'attributes' => $this->getAttributeBag($request, $action),
        ]);

        $result = app()->call([$action, 'run'], $bindings);

        return $this->response($result);
    }

    /**
     * Get attribute bag.
     *
     * @param  Request      $request
     * @param  mixed        $action
     * @return AttributeBag
     */
    protected function getAttributeBag(Request $request, $action)
    {
        return new AttributeBag($request->all()['attributes'] ?? []);
    }

    /**
     * Gets response for the given result.
     *
     * @param  mixed $result
     * @return mixed
     */
    protected function response($result)
    {
        if ($result instanceof SymfonyResponse) {
            return $result;
        }

        if ($result instanceof Response) {
            return $result;
        }

        return $this->defaultResponse();
    }

    /**
     * Get default response.
     *
     * @return JsonResponse
     */
    protected function defaultResponse()
    {
        return response()->success('Action executed.');
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
