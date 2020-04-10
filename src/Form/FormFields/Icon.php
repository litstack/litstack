<?php

namespace Fjord\Form\FormFields;

class Icon
{
    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'id',
        'title',
        'icons'
    ];

    const DEFAULTS = [

        'readonly' => false,
    ];
}
