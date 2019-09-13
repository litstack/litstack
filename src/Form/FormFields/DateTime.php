<?php

namespace AwStudio\Fjord\Form\FormFields;

class DateTime
{
    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'id',
        'title',
    ];

    const DEFAULTS = [
        'formatted' => 'llll',
        'no_label' => false,
        'inline' => false,
        'only_date' => true
    ];
}
