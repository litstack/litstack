<?php

namespace Ignite\Page\Wrapper;

use Ignite\Vue\Component;
use Ignite\Vue\Traits\StaticComponentName;

class CardWrapper extends Component
{
    use StaticComponentName;

    /**
     * The Vue component name.
     *
     * @var string
     */
    protected $name = 'lit-wrapper-card';

    /**
     * Set card to secondary.
     *
     * @return $this
     */
    public function secondary()
    {
        return $this->class('crud-card-secondary');
    }
}
