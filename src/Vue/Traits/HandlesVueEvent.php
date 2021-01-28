<?php

namespace Ignite\Vue\Traits;

use Illuminate\Http\Request;

trait HandlesVueEvent
{
    /**
     * Handle event.
     *
     * @param  Request $request
     * @return void
     */
    public function handleVueEvent(Request $request)
    {
        if (! class_exists($request->handler)) {
            abort(404, debug("Couldn't find event handler [{$request->handler}]."));
        }

        $handler = app()->make($request->handler);

        return app()->call([$handler, 'handle']);
    }
}
