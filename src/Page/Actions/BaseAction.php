<?php

namespace Ignite\Page\Actions;

use Ignite\Contracts\Page\ActionFactory;
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
        // Add title as content to wrapper.
        $wrapper = $this->getWrapper()->content($title);

        return new ActionComponent($action, $title, $wrapper);
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
