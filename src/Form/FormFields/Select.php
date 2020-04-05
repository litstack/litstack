<?php

namespace AwStudio\Fjord\Form\FormFields;

class Select
{
    const TRANSLATABLE = false;

    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'options',
        'title',
    ];

    const DEFAULTS = [
        'readonly' => false
    ];
}
