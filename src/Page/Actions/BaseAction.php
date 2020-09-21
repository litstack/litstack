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
    public function make($title, $action, $wrapper = null)
    {
        $actionInstance = app()->make($action);

        // Add title as content to wrapper.
        $wrapper = $this->getWrapper()->content($title);

        $component = component('lit-action')
            ->prop('wrapper', $wrapper)
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

    /**
     * Get wrapper.
     *
     * @param  string|null|Component $wrapper
     * @return Component
     */
    protected function getWrapper($wrapper = null)
    {
        return is_null($wrapper)
            ? $this->createComponent()
            : component($wrapper);
    }
}
