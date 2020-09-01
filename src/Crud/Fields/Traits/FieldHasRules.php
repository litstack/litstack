<?php

namespace Ignite\Crud\Fields\Traits;

use Ignite\Crud\CrudValidator;

trait FieldHasRules
{
    /**
     * Global rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Creation rules.
     *
     * @var array
     */
    protected $creationRules = [];

    /**
     * Update rules.
     *
     * @var array
     */
    protected $updateRules = [];

    /**
     * Validation rules.
     *
     * @param string ...$rules
     *
     * @return self
     */
    public function rules(...$rules)
    {
        $this->rules = array_merge($this->rules, $rules);

        return $this;
    }

    /**
     * Validation rules.
     *
     * @param string ...$rules
     *
     * @return self
     */
    public function creationRules(...$rules)
    {
        $this->creationRules = array_merge($this->creationRules, $rules);

        return $this;
    }

    /**
     * Validation rules.
     *
     * @param string ...$rules
     *
     * @return self
     */
    public function updateRules(...$rules)
    {
        $this->updateRules = array_merge($this->updateRules, $rules);

        return $this;
    }

    /**
     * Get rules for request.
     *
     * @param string|null $type
     *
     * @return array
     */
    public function getRules($type = null)
    {
        $rules = $this->rules;
        if ($type == CrudValidator::UPDATE) {
            $rules = array_merge($rules, $this->updateRules);
        }
        if ($type == CrudValidator::CREATION) {
            $rules = array_merge($rules, $this->creationRules);
        }

        return $rules;
    }
}
