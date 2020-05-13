<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Password extends Field
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-password';

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
        'placeholder',
        'minScore',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'minScore' => 2
    ];

    /**
     * Cast field value.
     *
     * @param mixed $value
     * @return boolean
     */
    public function cast($value)
    {
        return (string) $value;
    }

    /**
     * Format value before saving it to database.
     *
     * @param string $value
     * @return void
     */
    public function format($value)
    {
        return bcrypt($value);
    }
}
