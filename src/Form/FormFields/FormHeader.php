<?php

namespace Fjord\Form\FormFields;

class FormHeader
{
    /**
     * Forces Translatable to be false.
     *
     * @var boolean
     */
    const TRANSLATABLE = false;

    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'title',
    ];

    const DEFAULTS = [
        'readonly' => false,
        'block_width' => 12
    ];
}
