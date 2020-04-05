<?php

namespace Fjord\Form\FormFields;

class Range
{
    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'id',
        'title',
        'min',
        'max'
    ];

    const DEFAULTS = [
        'readonly' => false,
        'translatable' => false,
        'step' => 1,
    ];
}
