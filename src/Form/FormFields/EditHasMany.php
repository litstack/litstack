<?php

namespace Fjord\Form\FormFields;

use Illuminate\Support\Str;

class EditHasMany
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
        'readonly' => false,
    ];
}
