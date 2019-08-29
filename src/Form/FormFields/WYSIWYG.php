<?php

namespace AwStudio\Fjord\Form\FormFields;

class WYSIWYG
{
    // TODO: find out if this is needed
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
