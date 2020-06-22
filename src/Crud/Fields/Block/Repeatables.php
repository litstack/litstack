<?php

namespace Fjord\Crud\Fields\Block;

use Closure;
use Fjord\Vue\Table;
use Fjord\Support\VueProp;
use Fjord\Crud\Models\FormBlock;
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
    public $forms = [];

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
        $this->routePrefix = strip_slashes("{$field->route_prefix}/block/{$field->id}/{block_id}");
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param Closure $closure
     * @return void
     */
    public function add(string $name, Closure $closure)
    {
        $form = new BlockForm(FormBlock::class);

        $form->setRoutePrefix(
            $this->routePrefix
        );

        $preview = new Table;

        $closure($form, $preview);

        $this->forms[$name] = $form;
        $this->previews[$name] = $preview;

        return $this;
    }

    /**
     * Check if form exists.
     *
     * @param string $name
     * @return boolean
     */
    public function has(string $name)
    {
        return array_key_exists($name, $this->forms);
    }

    /**
     * Get form by name.
     *
     * @param string $name
     * @return BlockForm
     */
    public function get(string $name)
    {
        return $this->forms[$name] ?? null;
    }

    /**
     * To array.
     *
     * @return array
     */
    public function render(): array
    {
        $array = $this->forms;

        foreach ($array as $name => $form) {
            $array[$name] = [
                'form' => $form->toArray(),
                'preview' => $this->previews[$name]->toArray()
            ];
        }

        return $array;
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
