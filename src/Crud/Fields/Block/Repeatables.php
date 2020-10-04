<?php

namespace Ignite\Crud\Fields\Block;

use Closure;
use Ignite\Support\VueProp;
use Illuminate\Support\Traits\Macroable;

class Repeatables extends VueProp
{
    use Macroable;

    /**
     * Block field instance.
     *
     * @var Block
     */
    protected $field;

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
     * @param  Block $field
     * @return void
     */
    public function __construct(Block $field)
    {
        $this->field = $field;
    }

    /**
     * Add repeatable.
     *
     * @param  string       $type
     * @param  Closure|null $closure
     * @return Repeatable
     */
    public function add(string $type, Closure $closure = null)
    {
        if (class_exists($type)) {
            $rep = new $type($this->field);
        } else {
            $rep = new Repeatable($this->field, $type);
        }

        $rep->makeForm($closure);

        $this->repeatables[$rep->getType()] = $rep;

        return $rep;
    }

    /**
     * Add nested block repeatable.
     *
     * @param  string     $type
     * @param  Closure    $closure
     * @return Repeatable
     */
    public function block($type, Closure $closure)
    {
        return $this->add($type, fn ($form, $preview) => $form
            ->block($type)
            ->title($type)
            ->repeatables($closure))
            ->view('litstack::repeatables.block', [
                'type' => $type,
            ]);
    }

    /**
     * Check if form exists.
     *
     * @param  string $name
     * @return bool
     */
    public function has(string $name)
    {
        return array_key_exists($name, $this->repeatables);
    }

    /**
     * Get form by name.
     *
     * @param  string         $name
     * @return RepeatableForm
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
     *
     * @return void
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }
}
