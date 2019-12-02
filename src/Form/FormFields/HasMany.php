<?php

namespace AwStudio\Fjord\Form\FormFields;

use Illuminate\Support\Str;

class HasMany
{
    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'id',
        'model',
        'title',
    ];

    const DEFAULTS = [
    ];

}
