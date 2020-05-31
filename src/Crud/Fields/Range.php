<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Range extends Field
{
    use Concerns\FormItemWrapper;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-range';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = ['min', 'max'];

    /**
     * Set step.
     *
     * @param integer $step
     * @return $this
     */
    public function step(int $step)
    {
        $this->setAttribute('step', $step);

        return $this;
    }

    /**
     * Set max.
     *
     * @param integer $max
     * @return $this
     */
    public function max(int $max)
    {
        $this->setAttribute('max', $max);

        return $this;
    }

    /**
     * Set min.
     *
     * @param integer $min
     * @return $this
     */
    public function min(int $min)
    {
        $this->setAttribute('min', $min);

        return $this;
    }

    /**
     * Set default attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->setAttribute('step', 1);
        $this->setAttribute('min', 1);
    }

    /**
     * Cast field value.
     *
     * @param mixed $value
     * @return boolean
     */
    public function cast($value)
    {
        if (!is_numeric($value)) {
            return 0;
        }

        return is_float($value) ? (float) $value : (int) $value;
    }
}
