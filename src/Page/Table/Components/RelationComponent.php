<?php

namespace Ignite\Page\Table\Components;

class RelationComponent extends ColumnComponent
{
    /**
     * Related attribute name.
     *
     * @param  string $related
     * @return $this
     */
    public function related($related)
    {
        return $this->prop('related', $related);
    }

    /**
     * Related route prefix.
     *
     * @param  string $routePrefix
     * @return $this
     */
    public function routePrefix($routePrefix)
    {
        return $this->prop('routePrefix', $routePrefix);
    }

    /**
     * Deny to set link.
     *
     * @param  string $link
     * @return void
     */
    public function link($link)
    {
        return $this;
    }
}
