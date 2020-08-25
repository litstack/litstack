<?php

namespace Ignite\Contracts\Vue;

interface Renderable
{
    /**
     * Render class to pass it to a Vue component.
     *
     * @return mixed
     */
    public function render();
}
