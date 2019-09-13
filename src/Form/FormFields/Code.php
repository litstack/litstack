<?php

namespace AwStudio\Fjord\Form\FormFields;

class Code
{
    const TRANSLATABLE = false;

    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'id',
        'title',
    ];

    const DEFAULTS = [
        'tab_size' => 4,
        'theme' => 'paraiso-light',
        'line_numbers' => true,
        'line' => true,
        'language' => 'text/html'
    ];
}
