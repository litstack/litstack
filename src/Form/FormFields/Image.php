<?php

namespace Fjord\Form\FormFields;

class Image
{
    const TRANSLATABLE = true;

    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'id',
        'title',
    ];

    const DEFAULTS = [
        'readonly' => false,
        'maxFiles' => 1,
        'square' => true,
        'crop' => false,
        'ratio' => 4 / 3
    ];
}
