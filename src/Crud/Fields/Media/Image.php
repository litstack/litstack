<?php

namespace Fjord\Crud\Fields\Media;

use Fjord\Crud\MediaField;
use Fjord\Crud\Models\FormField;

class Image extends MediaField
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-form-media';

    /**
     * Is field translatable.
     *
     * @var boolean
     */
    protected $translatable = true;

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'hint',
        'imageSize',
        'maxFiles',
        'crop',
        'ratio',
        'square',
        'override'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'imageSize' => 12,
        'maxFiles' => 5,
        'crop' => false,
        'square' => false,
        'override' => false
    ];
}
