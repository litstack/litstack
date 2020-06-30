<?php

namespace Fjord\Crud\Fields\Block;

use Closure;
use Fjord\Support\VueProp;
use Fjord\Crud\Fields\Block\Block;
use Illuminate\Support\Traits\Macroable;

class Repeatables extends VueProp
{
    use Macroable;

    /**
     * Registered forms.
     *
     * @var array
     */
    public $repeatables = [];

    /**
     * Preview tables.
     *
     * @var array
     */
    public $previews = [];

    /**
     * Route prefix.
     *
     * @var string
     */
    protected $routePrefix;

    /**
     * Create new Repeatables instance.
     *
     * @param Block $field
     */
    public function __construct(Block $field)
    {
        $this->field = $field;
    }

    /**
     * Add repeatable.
     *
     * @param string $name
     * @param Closure $closure
     * @return 
     */
    public function add(string $name, Closure $closure)
    {
        $rep = new Repeatable($this->field, $name);

        $rep->form($closure);

        $this->repeatables[$name] = $rep;

        return $rep;
    }

    /**
     * Check if form exists.
     *
     * @param string $name
     * @return boolean
     */
    public function has(string $name)
    {
        return array_key_exists($name, $this->repeatables);
    }

    /**
     * Get form by name.
     *
     * @param string $name
     * @return BlockForm
     */
    public function get($name)
    {
        return $this->repeatables[$name] ?? null;
    }

    /**
     * To array.
     *
     * @return array
     */
    public function render(): array
    {
        $rendered = [];

        foreach ($this->repeatables as $name => $rep) {
            $rendered[$name] = $rep->render();
        }

        return $rendered;
    }

    /**
     * Get form by key.
     *
     * @param string $key
     * @return void
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }
}
