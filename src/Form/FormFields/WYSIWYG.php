<?php

namespace AwStudio\Fjord\Form\FormFields;

class WYSIWYG
{
    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'id',
        'title',
    ];

    const DEFAULTS = [
        'translatable' => false
    ];
}
