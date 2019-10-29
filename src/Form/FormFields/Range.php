<?php

namespace AwStudio\Fjord\Form\FormFields;

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
        'translatable' => false,
        'step' => 1,
    ];


}
