<?php

namespace Fjord\Page;

use Illuminate\Http\Request;

class RunActionEvent
{
    public function handle(Request $request)
    {
        if (! class_exists($request->action)) {
            abort(404, debug("Couldn't find action [{$request->action}]."));
        }

        $action = app()->make($request->action);

        return app()->call([$action, 'run']);
    }
}
