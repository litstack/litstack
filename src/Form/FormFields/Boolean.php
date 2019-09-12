<?php

namespace AwStudio\Fjord\Form\FormFields;

class Boolean
{
    const TRANSLATABLE = false;

    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'id',
        'title',
    ];

    const DEFAULTS = [
        'default' => true
    ];
}
