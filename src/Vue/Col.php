<?php

namespace Fjord\Vue;

use Exception;
use Fjord\Application\Config\ConfigItem;

class Col extends ConfigItem
{
    /**
     * Table column attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create new Col instance.
     *
     * @param string $label
     */
    public function __construct(string $label = null)
    {
        if ($label) {
            $this->label($label);
        }
    }

    /**
     * Set value.
     *
     * @param string $value
     * @return self
     */
    public function value(string $value)
    {
        $this->attributes['value'] = $value;

        return $this;
    }

    /**
     * Set link.
     *
     * @param string|boolean $link
     * @return self
     */
    public function link($link)
    {
        $this->attributes['link'] = $link;

        return $this;
    }

    /**
     * Set sort_by.
     *
     * @param string $key
     * @return self
     */
    public function sortBy(string $key)
    {
        $this->attributes['sort_by'] = $key;

        return $this;
    }

    /**
     * Set label.
     *
     * @param string $label
     * @return self
     */
    public function label(string $label)
    {
        $this->attributes['label'] = $label;

        return $this;
    }

    /**
     * Set Vue component name.
     *
     * @param string $component
     * @return self
     */
    public function component(string $component)
    {
        $this->attributes['component'] = $component;

        return $this;
    }

    /**
     * Set component props.
     *
     * @param array $props
     * @return self
     */
    public function props(array $props)
    {
        if (!array_key_exists('component', $this->attributes)) {
            throw new Exception('Props can only be passed to Vue component table columns. You many call "component" before "props".');
        }

        $this->attributes['props'] = $props;

        return $this;
    }
}
