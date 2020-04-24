<?php

namespace Fjord\Crud\Fields\Blocks;

use Closure;
use Fjord\Support\VueProp;
use Fjord\Crud\Models\FormBlock;

class Repeatables extends VueProp
{
    /**
     * Registered forms.
     *
     * @var array
     */
    protected $forms = [];

    /**
     * Route prefix.
     *
     * @var string
     */
    protected $routePrefix;

    /**
     * Create new Repeatables instance.
     *
     * @param string|null $routePrefix
     */
    public function __construct($routePrefix)
    {
        $this->routePrefix = strip_slashes($routePrefix . '/blocks/{block_id}');
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

        $closure($form);

        $this->forms[$name] = $form;

        return $this;
    }

    /**
     * To array.
     *
     * @return array
     */
    public function getArray(): array
    {
        $array = $this->forms;

        foreach ($array as $name => $form) {
            $array[$name] = $form->toArray();
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
        return $this->forms[$key];
    }
}
