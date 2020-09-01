<?php

namespace Ignite\Contracts\Page;

interface ActionFactory
{
    /**
     * Create an action component.
     *
     * @param  string            $title
     * @param  string            $action
     * @return Lit\Vue\Component
     */
    public function make($title, $action);
}
