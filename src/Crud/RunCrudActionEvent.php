<?php

namespace Ignite\Crud;

use Ignite\Page\RunActionEvent;
use Ignite\Support\Facades\Config;
use Illuminate\Http\Request;

class RunCrudActionEvent extends RunActionEvent
{
    /**
     * Handle RunActionEvent.
     *
     * @param  Request $request
     * @return mixed
     */
    public function handle(Request $request)
    {
        $config = Config::get($request->config);

        if (! $config || ! $config->has('controller')) {
            abort(404, debug("Invalid config [$request->config] for action {$request->action}."));
        }

        $request->route()->config($request->config);

        return parent::handle($request);
    }

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
