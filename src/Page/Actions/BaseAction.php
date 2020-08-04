<?php

namespace Fjord\Page\Actions;

use Fjord\Contracts\Page\ActionFactory;
use Fjord\Page\RunActionEvent;
use Fjord\Vue\Component;

abstract class BaseAction implements ActionFactory
{
    /**
     * Create component instance.
     *
     * @return mixed
     */
    abstract protected function createComponent();

    /**
     * Create an action component.
     *
     * @param  string    $action
     * @return Component
     */
    public function make($title, $action)
    {
        $actionInstance = app()->make($action);

        $component = component('fj-action')
            ->prop('wrapper', $this->createComponent()->content($title))
            ->on('run', RunActionEvent::class)
            ->prop('eventData', ['action' => $action]);

        if (method_exists($actionInstance, 'modal')) {
            $component->prop('modal', $modal = new ActionModal);

            $actionInstance->modal(
                $modal->title($title)
            );
        }

        return $component;
    }
}
