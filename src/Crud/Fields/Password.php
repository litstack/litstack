<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\BaseField;
use Illuminate\Support\Facades\Hash;

class Password extends BaseField
{
    use Traits\FieldHasRules;
    use Traits\FieldHasPlaceholder;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-password';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Use only rules and dont store value.
     *
     * @var bool
     */
    protected $rulesOnly = false;

    /**
     * Fill model.
     *
     * @param mixed   $model
     * @param Request $request
     * @param string  $attributeName
     * @param mixed   $attributeValue
     *
     * @return void
     */
    public function fillModel($model, $attributeName, $attributeValue)
    {
        if ($this->rulesOnly) {
            return;
        }

        $model->{$attributeName} = $attributeValue;
    }

    /**
     * Set default attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->minScore(1);
        $this->noScore(false);
    }

    /**
     * Only rules.
     *
     * @param bool $noScore
     *
     * @return $this
     */
    public function rulesOnly(bool $rulesOnly = true)
    {
        $this->rulesOnly = $rulesOnly;

        return $this;
    }

    /**
     * DEPRECATED use rulesOnly.
     *
     * @param bool $dontStore
     *
     * @return $this
     */
    public function dontStore(bool $dontStore = true)
    {
        $this->rulesOnly($dontStore);

        return $this;
    }

    /**
     * Set noScore.
     *
     * @param bool $noScore
     *
     * @return $this
     */
    public function noScore(bool $noScore = true)
    {
        $this->setAttribute('noScore', $noScore);

        return $this;
    }

    /**
     * Set minScore.
     *
     * @param int $score
     *
     * @return $this
     */
    public function minScore(int $score)
    {
        // TODO: find a good way to implement minScore
        $this->setAttribute('minScore', $score);

        return $this;
    }

    /**
     * Confirm the form using the current password.
     *
     * @param Type $var
     *
     * @return self
     */
    public function confirm($confirm = true)
    {
        if (!$confirm) {
            return $this;
        }

        $confirmationRule = function ($attribute, $value, $fail) {
            if (!Hash::check($value, fjord_user()->password)) {
                return $fail(__f('validation.incorrect_password'));
            }
        };

        return $this->rulesOnly()
            ->noScore()
            ->hint('Confirm with current password.')
            ->rules('required', $confirmationRule);
    }

    /**
     * Cast field value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function cast($value)
    {
        return (string) $value;
    }

    /**
     * Format value before saving it to database.
     *
     * @param string $value
     *
     * @return void
     */
    public function format($value)
    {
        return bcrypt($value);
    }
}
