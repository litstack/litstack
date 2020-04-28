<?php

namespace Fjord\Vue\Components\Index;

use Fjord\Vue\TableComponent;

class ColCrudRelationComponent extends TableComponent
{
    /**
     * Available component props.
     *
     * @var array
     */
    protected $available = [
        'related',
        'value',
        'route_prefix'
    ];

    /**
     * Required props.
     *
     * @var array
     */
    protected $required = [
        'related',
        'value',
        'route_prefix'
    ];

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
