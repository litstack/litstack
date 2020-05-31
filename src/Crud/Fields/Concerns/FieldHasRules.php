<?php

namespace Fjord\Crud\Fields\Concerns;

use Closure;
use Fjord\Crud\Requests\CrudCreateRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;

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
     * @param CrudUpdateRequest|CrudCreateRequest $request
     * @return array
     */
    public function getRules($request)
    {
        $rules = $this->rules;
        if ($request instanceof CrudUpdateRequest) {
            $rules = array_merge($rules, $this->updateRules);
        }
        if ($request instanceof CrudCreateRequest) {
            $rules = array_merge($rules, $this->creationRules);
        }
        return $rules;
    }
}
