<?php

namespace AwStudio\Fjord\Fjord\FormFields;

class WYSIWYG
{
    const TRANSLATABLE = true;

    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'id'
    ];

    const DEFAULTS = [
        'translatable' => false
    ];
}
