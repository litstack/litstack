<?php

namespace Ignite\Crud\Fields;

use Ignite\Crud\BaseField;
use Illuminate\Support\Facades\Hash;
use LogicException;

class Password extends BaseField
{
    use Traits\FieldHasRules;
    use Traits\FieldHasPlaceholder;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-password';

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
     * Set model class.
     *
     * @param  string $model
     * @return void
     *
     * @throws LogicException
     */
    public function setModel($model)
    {
        parent::setModel($model);

        if (in_array($this->id, (new $model)->getFillable())) {
            throw new LogicException(
                "Remove [{$this->id}] from your fillable attributes in [{$model}] in order to use the password field.",
            );
        }
    }

    /**
     * Fill model.
     *
     * @param mixed  $model
     * @param string $attributeName
     * @param mixed  $attributeValue
     *
     * @return void
     */
    public function fillModel($model, $attributeName, $attributeValue)
    {
        if ($this->rulesOnly) {
            return;
        }

        $model->{$attributeName} = bcrypt($attributeValue);
    }

    /**
     * Set default attributes.
     *
     * @return void
     *
     * @throws LogicException
     */
    public function mount()
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
     * @param  bool  $dontStore
     * @return $this
     *
     * @deprecated
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
        if (! $confirm) {
            return $this;
        }

        $confirmationRule = function ($attribute, $value, $fail) {
            if (! Hash::check($value, lit_user()->password)) {
                return $fail(__lit('validation.incorrect_password'));
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
    public function castValue($value)
    {
        return (string) $value;
    }
}
