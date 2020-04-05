<?php

namespace Fjord\Form\FormFields;

class Block
{
    /**
     * Forces Translatable to be false.
     *
     * @var boolean
     */
    const TRANSLATABLE = false;

    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'id',
        'repeatables',
        'title',
    ];

    const DEFAULTS = [
        'readonly' => false,
        'block_width' => 12
    ];

    public static function prepare($field, $path)
    {
        $repeatables = [];
        foreach ($field->repeatables as $title) {
            $preparedPath = str_replace('.', '/', $title);
            $repeatables[$title] = require fjord_resource_path("repeatables/{$preparedPath}.php");
        }

        $field->repeatables = $repeatables;
        return $field;
    }
}
