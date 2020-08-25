<?php

namespace Ignite\Page\Actions;

use Ignite\Contracts\Page\ActionFactory;
use Ignite\Page\RunActionEvent;
use Ignite\Vue\Component;

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

        $component = component('lit-action')
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
