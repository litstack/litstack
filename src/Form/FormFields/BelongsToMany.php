<?php

namespace Fjord\Form\FormFields;

use Illuminate\Support\Str;

class BelongsToMany
{
    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'model',
        'id',
        'preview',
        'title',
    ];

    const DEFAULTS = [
        'readonly' => false,
    ];
}
