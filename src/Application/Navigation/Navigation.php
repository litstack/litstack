<?php

namespace Ignite\Application\Navigation;

use Ignite\Support\VueProp;

class Navigation extends VueProp
{
    /**
     * Navigation entries.
     *
     * @var array
     */
    protected $entries = [];

    /**
     * Navigation preset factory instance.
     *
     * @var PresetFactory
     */
    protected $factory;

    /**
     * Create new Navigation instance.
     *
     * @param  PresetFactory $factory
     * @return void
     */
    public function __construct(PresetFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Navigation title.
     *
     * @param  string $title
     * @return Title
     */
    public function title(string $title)
    {
        return new Title($title);
    }

    /**
     * Navigation group.
     *
     * @param  string|array $title
     * @param  array        $children
     * @return Group
     */
    public function group($title, array $children = [])
    {
        $params = [];
        if (is_array($title)) {
            $params = $title;
            $title = $title['title'] ?? '';
        }

        if (! $this->authorize($params)) {
            return;
        }

        return new Group($title, $children, $params);
    }

    /**
     * Navigation entry.
     *
     * @param  string|array $title
     * @param  array        $params
     * @return Entry
     */
    public function entry($title, array $params = [])
    {
        // Allow params only.
        if (is_array($title)) {
            $params = $title;
            $title = $title['title'];
        }

        if (! $this->authorize($params)) {
            return;
        }

        return new Entry($title, $params);
    }

    /**
     * Navigation entry preset.
     *
     * @param  string $name
     * @param  array  $params
     * @return array
     */
    public function preset(string $name, array $params = [])
    {
        return $this->entry(
            $this->factory->getPreset($name, $params)
        );
    }

    /**
     * Add navigation section.
     *
     * @param  array $entries
     * @return void
     */
    public function section(array $entries)
    {
        $this->entries[] = $entries;

        return $this;
    }

    public function render(): array
    {
        return $this->entries;
    }

    /**
     * Authorize navigation entry.
     *
     * @param  array $params
     * @return bool
     */
    protected function authorize(array $params)
    {
        if (! array_key_exists('authorize', $params)) {
            return true;
        }

        if (! is_callable($params['authorize'])) {
            return $params['authorize'];
        }

        return $params['authorize'](lit_user());
    }
}
