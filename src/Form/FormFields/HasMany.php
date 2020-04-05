<?php

namespace Fjord\Form\FormFields;

use Illuminate\Support\Str;

class HasMany
{
    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'id',
        'model',
        'foreign_key',
        'preview',
        'title',
    ];

    const DEFAULTS = [
        'readonly' => false,
    ];
}
