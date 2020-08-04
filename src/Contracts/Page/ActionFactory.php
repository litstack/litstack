<?php

namespace Fjord\Contracts\Page;

interface ActionFactory
{
    /**
     * Create an action component.
     *
     * @param  string              $title
     * @param  string              $action
     * @return Fjord\Vue\Component
     */
    public function make($title, $action);
}
