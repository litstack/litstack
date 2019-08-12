<?php

namespace AwStudio\Fjord\Fjord\FormFields;

class Boolean
{
    const TRANSLATABLE = false;

    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'id'
    ];

    const DEFAULTS = [
        'default' => true
    ];
}
