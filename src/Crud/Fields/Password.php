<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;
use Illuminate\Support\Facades\Hash;
use Fjord\Crud\Fields\Concerns\FieldHasRules;

class Password extends Field
{
    use FieldHasRules;

    /**
     * Fill to attribute.
     *
     * @var boolean
     */
    public $fill = false;


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
        'noScore',
        'hint',
        'rules',
        'confirm',
        'updateRules',
        'creationRules',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'minScore' => 2,
        'noScore' => false,
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

    /**
     * Dont store in database.
     *
     * @param boolean $dont
     * @return void
     */
    public function dontStore($dont = true)
    {
        $this->save = !$dont;

        return $this;
    }

    /**
     * Confirm only.
     *
     * @param Type $var
     * @return void
     */
    public function confirm($confirm = true)
    {
        if (!$confirm) {
            return $this;
        }

        $this->save = false;
        $this->noScore();
        $this->hint('Confirm with current password.');
        $this->rules('required', function ($attribute, $value, $fail) {
            if (!Hash::check($value, fjord_user()->password)) {
                return $fail(__f('validation.incorrect_password'));
            }
        });

        return $this;
    }
}
