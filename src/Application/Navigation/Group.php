<?php

namespace Ignite\Application\Navigation;

use Closure;

class Group extends NavigationItem
{
    const TYPE = 'group';

    /**
     * Create new Title instance.
     *
     * @param  string $title
     * @param  array  $children
     * @param  array  $options
     * @return void
     */
    public function __construct(string $title, array $children = [], array $options = [])
    {
        $this->attributes = $options;
        $this->setAttribute('children', $children);
        $this->setAttribute('title', $title);
    }

    /**
     * Set entry title.
     *
     * @param  string $title
     * @return $this
     */
    public function title($title)
    {
        $this->setAttribute('title', $title);

        return $this;
    }

    /**
     * Add child entry.
     *
     * @param  Entry  $entry
     * @return $this;
     */
    public function child(Entry $entry)
    {
        $this->attributes['children'][] = $entry;

        return $this;
    }

    /**
     * Set entry icon.
     *
     * @param  string $title
     * @return $this
     */
    public function icon($icon)
    {
        $this->setAttribute('icon', $icon);

        return $this;
    }

    /**
     * Set authorize closure.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function authorize(Closure $closure)
    {
        $this->setAttribute('authorize', $closure);

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function render(): array
    {
        $this->setAttribute('type', self::TYPE);

        return $this->attributes;
    }
}
