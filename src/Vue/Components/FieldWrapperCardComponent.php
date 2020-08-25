<?php

namespace Ignite\Vue\Components;

use Ignite\Contracts\Vue\Resizable;
use Ignite\Vue\Component;
use Ignite\Vue\Traits\CanBeResized;

class FieldWrapperCardComponent extends Component implements Resizable
{
    use CanBeResized;

    /**
     * Handle beforeMount.
     *
     * @return void
     */
    public function beforeMount()
    {
        $this->class('mb-4');
        $this->width(12);
    }

    /**
     * Set the title.
     *
     * @param  string $title
     * @return $this
     */
    public function title($title)
    {
        return $this->prop('title', $title);
    }
}
