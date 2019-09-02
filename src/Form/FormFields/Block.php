<?php

namespace AwStudio\Fjord\Form\FormFields;

class Block
{
    const TRANSLATABLE = false;

    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'id',
        'repeatables'
    ];

    const DEFAULTS = [
        'block_width' => 12
    ];

    public static function prepare($field, $path) {
        $repeatables = [];
        foreach($field->repeatables as $title) {
            $preparedPath = str_replace('.', '/', $title);
            $repeatables[$title] = require fjord_resource_path("repeatables/{$preparedPath}.php");
        }

        $field->repeatables = $repeatables;
        return $field;
    }
}
