<?php

namespace Lit\Crud;

use Lit\Page\RunActionEvent;
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
        if (! $request->ids) {
            return ['models' => collect([])];
        }

        return [
            'models' => $request->model::whereIn('id', $request->ids)->get(),
        ];
    }
}
