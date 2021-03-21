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
     * @param  string|array $routePrefix
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
        if (is_array($config)) {
            $prefixes = [];
            foreach ($config as $model => $c) {
                $prefixes[$model] = Config::get($c)->route_prefix;
            }

            return $this->routePrefix($prefixes);
        } else {
            return $this->routePrefix(Config::get($config)->route_prefix);
        }
    }

    /**
     * Set value.
     *
     * @param  string|array $value
     * @param  array|null   $options
     * @param  mixed        $default
     * @return $this
     */
    public function value($value, array $options = null, $default = null)
    {
        if (is_array($value)) {
            return parent::value("{$this->related}_type", $value);
        } else {
            return parent::value($value);
        }
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
