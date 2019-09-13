<?php

namespace AwStudio\Fjord\Form\FormFields;

class Code
{
    const TRANSLATABLE = false;

    // TODO: 'type' => 'string' / 'type' => ['string', 'array']
    const REQUIRED = [
        'type',
        'id',
        'title',
    ];

    const DEFAULTS = [
        'tab_size' => 4,
        'theme' => 'paraiso-light',
        'line_numbers' => true,
        'line' => true,
        'language' => 'text/html'
        //'''{name: "htmlmixed",scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,mode: null},{matches: /(text|application)\/(x-)?vb(a|script)/i,mode: "vbscript"}]}'''
    ];

    /*
    const ALLOWED_LANGUAGES = [
        'text/html',
    ];

    public static function prepare($field, $path)
    {
        if(! in_array($field->input_type, self::ALLOWED_LANGUAGES)) {
            throw new Exception("Invalid code language \"{$field->input_type}\", allowed languages are: " . implode(', ', self::ALLOWED_LANGUAGES));
        }
    }
    */
}
