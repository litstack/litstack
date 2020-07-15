<?php

namespace Fjord\Crud;

use Fjord\Page\RunActionEvent;
use Illuminate\Http\Request;

class RunCrudActionEvent extends RunActionEvent
{
    /**
     * Get action bindings.
     *
     * @return array
     */
    protected function getBindings(Request $request)
    {
        return [
            'models' => $request->model::whereIn('id', $request->ids)->get(),
        ];
    }
}
