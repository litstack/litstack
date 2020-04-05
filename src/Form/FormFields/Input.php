<?php

namespace AwStudio\Fjord\Form\FormFields;

use Exception;

class Input
{
    const TRANSLATABLE = true;

    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'id',
        'title',
    ];

    const DEFAULTS = [
        'translatable' => false,
        'input_type' => 'text',
        'readonly' => false,
    ];

    const ALLOWED_INPUT_TYPES = [
        'text',
        'number',
        'email',
        'range',
        'color'
    ];

    public static function prepare($field, $path)
    {
        if (!in_array($field->input_type, self::ALLOWED_INPUT_TYPES)) {
            throw new Exception("Invalid form field input_type \"{$field->input_type}\", allowed input types are: " . implode(', ', self::ALLOWED_INPUT_TYPES));
        }
    }
}
