<?php

namespace Fjord\Vue\Components\Index;

use Fjord\Vue\TableComponent;

class ColToggleComponent extends TableComponent
{
    /**
     * Prop options.
     *
     * @return array
     */
    protected function props()
    {
        $props = [
            'routePrefix' => [
                'type' => 'string',
                'required' => true
            ],
        ];

        return array_merge(
            parent::props(),
            $props
        );
    }
}
