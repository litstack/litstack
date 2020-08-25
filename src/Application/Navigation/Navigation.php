<?php

namespace Ignite\Application\Navigation;

use Ignite\Support\Facades\Package;
use Illuminate\Contracts\Support\Arrayable;

class Navigation implements Arrayable
{
    /**
     * Navigation entries.
     *
     * @var array
     */
    protected $entries = [];

    /**
     * Navigation title.
     *
     * @param  string $title
     * @return string $title
     */
    public function title(string $title)
    {
        return [
            'title' => $title,
            'type'  => 'title',
        ];
    }

    /**
     * Navigation group.
     *
     * @param  array $params
     * @param  array $children
     * @return array $entry
     */
    public function group(array $params, array $children = [])
    {
        if (! $this->authorize($params)) {
            return;
        }

        $params['type'] = 'group';

        return array_merge([
            'children' => $children,
        ], $params);
    }

    /**
     * Navigation entry.
     *
     * @param  string|array $title
     * @param  array        $params
     * @return array        $entry
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

        return array_merge($params, [
            'title' => $title,
            'type'  => 'entry',
        ]);
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
            Package::navPreset($name, $params)
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

    /**
     * To array.
     *
     * @return array
     */
    public function toArray()
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
