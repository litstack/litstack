<?php

namespace Ignite\Vue;

use Illuminate\Http\Request;

class EventController
{
    /**
     * Handle event.
     *
     * @param  Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        if (! class_exists($request->handler)) {
            abort(404, debug("Couldn't find event handler [{$request->handler}]."));
        }

        $handler = app()->make($request->handler);

        return app()->call([$handler, 'handle']);
    }
}
