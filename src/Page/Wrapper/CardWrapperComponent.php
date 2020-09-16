<?php

namespace Ignite\Page\Wrapper;

use Ignite\Contracts\Vue\Resizable;
use Ignite\Vue\Component;
use Ignite\Vue\Traits\CanBeResized;
use Ignite\Vue\Traits\StaticComponentName;

class CardWrapperComponent extends Component implements Resizable
{
    use StaticComponentName, CanBeResized;

    /**
     * The Vue component name.
     *
     * @var string
     */
    protected $name = 'lit-wrapper-card';

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
     * Set card to secondary.
     *
     * @return $this
     */
    public function secondary()
    {
        return $this->class('crud-card-secondary');
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
