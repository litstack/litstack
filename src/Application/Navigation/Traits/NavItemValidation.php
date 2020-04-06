<?php

namespace Fjord\Application\Navigation\Traits;

use Illuminate\Support\Str;

trait NavItemValidation
{
    /**
     * Check navigation items.
     *
     * @return array $checkedItems
     */
    protected function checkItems($items)
    {
        $checkedItems = [];

        foreach ($this->attributes as $group) {
            $group = $this->convertGroup($group);
            $group['items'] = $this->validateItems($group['items']);

            // Only add groups that have items.
            if ($group['items'] === false) {
                continue;
            }

            $checkedItems[] = array_merge([$group['title'] ?? null], $group['items']);
        }

        return $checkedItems;
    }

    /**
     * Convert group.
     *
     * @param array $group
     * @return array $group
     */
    protected function convertGroup(array $group)
    {
        $convertedGroup = [];

        if (is_string($group[0])) {
            $convertedGroup['title'] = array_shift($group);
        }

        $convertedGroup['items'] = $group;

        return $convertedGroup;
    }

    /**
     * Only pass validated items.
     *
     * @param array $items
     * @return boolean|array
     */
    protected function validateItems(array $items)
    {
        $validatedItems = [];

        foreach ($items as $item) {

            // Only add validated items.
            if ($this->validateItem($item) === false) {
                continue;
            }

            if (array_key_exists('link', $item)) {
                $item['link'] = $this->getLink($item['link']);
            }

            $validatedItems[] = $item;
        }

        if (empty($validatedItems)) {
            return false;
        }

        return $validatedItems;
    }

    /**
     * Validate item.
     *
     * @param array $item
     * @return void
     */
    protected function validateItem(array $item)
    {
        // When the item doesnt has a link it is a group 
        // and should have children.
        if (!array_key_exists('link', $item)) {

            // Dont allow item without link and children.
            if (!array_key_exists('children', $item)) {
                return false;
            }

            // Validate children items.
            $item['children'] = $this->validateItems($item['children']);

            // Dont allow item without validated children.
            if ($item['children'] === false) {
                return false;
            }

            return $item;
        }

        if (array_key_exists('permission', $item)) {

            // Dont allow item if authenticated user doesnt have permission.
            if (!fjord_user()->can($item['permission'])) {
                return false;
            }
        }

        return $item;
    }

    /**
     * Create link for Fjord navigation.
     *
     * @param string $link
     * @return string $link
     */
    protected function getLink(string $link)
    {
        if (Str::contains($link, '://')) {
            $split = explode('/', str_replace('://', '', $link));
            array_shift($split);
            return "/" . implode('/', $split);
        }

        $fjordPrefix = preg_replace('#/+#', '/', "/" . config('fjord.route_prefix'));
        $link = preg_replace('#/+#', '/', "/" . $link);
        if (Str::startsWith($link, $fjordPrefix)) {
            return $link;
        }

        return $fjordPrefix . $link;
    }
}
