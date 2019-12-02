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
        'foreign_key',
        'form',
        'title',
    ];

    const DEFAULTS = [
    ];

}
