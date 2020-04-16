<?php

namespace Fjord\Application\Navigation;

use Fjord\Support\Facades\Package;

class FjordNavigation
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
     * @param string $title
     * @return string $title
     */
    public function title(string $title)
    {
        return [
            'title' => $title,
            'type' => 'title',
        ];
    }

    /**
     * Navigation group.
     *
     * @param array $params
     * @param array $children
     * @return array $entry
     */
    public function group(array $params, array $children = [])
    {
        if (!$this->authorize($params)) {
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
     * @param string|array $title
     * @param array $params
     * @return array $entry
     */
    public function entry($title, array $params = [])
    {
        // Allow params only.
        if (is_array($title)) {
            $params = $title;
            $title = $title['title'];
        }

        if (!$this->authorize($params)) {
            return;
        }

        return array_merge($params, [
            'title' => $title,
            'type' => 'entry'
        ]);
    }

    /**
     * Navigation entry preset.
     *
     * @param string $name
     * @param array $params
     * @return array $entry
     */
    public function preset(string $name, array $params = [])
    {
        return $this->entry(Package::navEntry($name, $params));
    }

    /**
     * Navigation section.
     *
     * @param array $entries
     * @return void
     */
    public function section(array $entries)
    {
        $this->entries[] = $entries;
    }

    /**
     * Get entires.
     *
     * @return array $entries
     */
    public function getEntries()
    {
        return $this->entries;
    }

    protected function authorize(array $params)
    {
        if (!array_key_exists('authorize', $params)) {
            return true;
        }

        if (!is_callable($params['authorize'])) {
            return $params['authorize'];
        }

        return $params['authorize'](fjord_user());
    }
}
