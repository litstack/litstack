<?php

namespace Ignite\Application\Navigation;

class Title extends NavigationItem
{
    const TYPE = 'title';

    /**
     * Create new Title instance.
     *
     * @param  string $title
     * @return void
     */
    public function __construct(string $title)
    {
        $this->setAttribute('title', $title);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function render(): array
    {
        return [
            'title' => $this->title,
            'type'  => self::TYPE,
        ];
    }
}
