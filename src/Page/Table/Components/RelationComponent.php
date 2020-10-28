<?php

namespace Ignite\Page\Table\Components;

use Ignite\Support\Facades\Config;

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
     * Set route prefix from crud config.
     *
     * @param  string $config
     * @return $this
     */
    public function crud($config)
    {
        return $this->routePrefix(Config::get($config)->route_prefix);
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
