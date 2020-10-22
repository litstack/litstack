<?php

namespace Ignite\Application\Navigation;

use Closure;

class Entry extends NavigationItem
{
    const TYPE = 'entry';

    /**
     * Create new Title instance.
     *
     * @param  string $title
     * @param  array  $options
     * @return void
     */
    public function __construct(string $title, array $options = [])
    {
        $this->attributes = $options;
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
