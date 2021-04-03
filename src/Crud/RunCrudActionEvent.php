<?php

namespace Ignite\Crud;

use Ignite\Page\Actions\ActionComponent;
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
     * Get attribute bag.
     *
     * @param  Request      $request
     * @param  mixed        $action
     * @return AttributeBag
     */
    protected function getAttributeBag(Request $request, $action)
    {
        $attributes = parent::getAttributeBag($request, $action);

        if (! method_exists($action, 'modal')) {
            return $attributes;
        }

        $modal = (new ActionComponent(get_class($action), ''))->getModal();

        if (! $form = $modal->getForm()) {
            return $attributes;
        }

        CrudValidator::validate($attributes->toArray(), $form);

        return $attributes;
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
