<?php

namespace Fjord\Vue\Components\Index;

use Fjord\Vue\TableComponent;

class ColImageComponent extends TableComponent
{
    /**
     * Prop options.
     *
     * @return array
     */
    protected function props()
    {
        $props = [
            'src' => [
                'type' => 'string',
                'required' => true
            ],
            'maxWidth' => [
                'type' => 'string'
            ],
            'maxHeight' => [
                'type' => 'string'
            ],
        ];

        return array_merge(
            parent::props(),
            $props
        );
    }
}
