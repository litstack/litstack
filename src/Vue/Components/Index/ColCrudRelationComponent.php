<?php

namespace Fjord\Vue\Components\Index;

use Fjord\Vue\TableComponent;

class ColCrudRelationComponent extends TableComponent
{
    /**
     * Prop options.
     *
     * @return array
     */
    protected function props()
    {
        $props = [
            'related' => [
                'type' => 'string',
                'required' => true
            ],
            'value' => [
                'type' => 'string',
                'required' => true
            ],
            'route_prefix' => [
                'type' => 'string',
                'required' => true
            ],
        ];

        return array_merge(
            parent::props(),
            $props
        );
    }

    /**
     * Deny to set link.
     *
     * @param string $link
     * @return void
     */
    public function link($link)
    {
        return;
    }
}
