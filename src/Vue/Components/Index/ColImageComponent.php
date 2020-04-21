<?php

namespace Fjord\Vue\Components\Index;

use Fjord\Vue\TableComponent;

class ColImageComponent extends TableComponent
{
    /**
     * Available component props.
     *
     * @var array
     */
    protected $available = [
        'src',
        'maxWidth',
        'maxHeight'
    ];

    /**
     * Required props.
     *
     * @var array
     */
    protected $required = [
        'src'
    ];
}
