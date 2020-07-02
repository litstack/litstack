<?php

namespace Fjord\Crud;

use Fjord\Support\VueProp;
use Illuminate\Support\Str;
use InvalidArgumentException;

class FieldDependency extends VueProp
{
    /**
     * List of available conditons. All conditions are available as [or{condition}].
     *
     * @var array
     */
    protected $conditions = [
        'when',
        'whenContains',
    ];

    /**
     * Dependency condition type.
     *
     * @var string
     */
    protected $condition;

    /**
     * Field dependant.
     *
     * @var Field
     */
    protected $dependent;

    /**
     * Dependency value.
     *
     * @var string
     */
    protected $value;

    /**
     * Create new Dependency.
     *
     * @param  string     $contains
     * @param  Field      $dependent
     * @param  string|int $value
     * @return void
     */
    public function __construct(string $condition, Field $dependent, $value)
    {
        if (! $this->conditionExists($condition)) {
            throw new InvalidArgumentException(
                "Condition [{$condition}] is not available"
            );
        }

        $this->condition = $condition;
        $this->dependent = $dependent;
        $this->value = $value;
    }

    /**
     * Determines if a condition exists.
     *
     * @param  string $condition
     * @return bool
     */
    public function conditionExists(string $condition)
    {
        if (! Str::startsWith($condition, 'or')) {
            return in_array($condition, $this->conditions);
        }

        foreach ($this->conditions as $available) {
            $orCondition = 'or'.ucfirst($available);
            if ($orCondition == $condition) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create new Dependency instance.
     *
     * @param  string     $condition
     * @param  string|int $value
     * @return self
     */
    public static function make(string $condition, Field $dependent, $value)
    {
        return new self($condition, $dependent, $value);
    }

    /**
     * Render dependency for Vue.
     *
     * @return array
     */
    public function render(): array
    {
        return [
            'condition' => $this->condition,
            'attribute' => $this->dependent->id,
            'value'     => $this->value,
        ];
    }
}
