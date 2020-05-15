<?php

namespace Fjord\Crud\Fields\Blocks;

use Closure;
use Fjord\Vue\Table;
use Fjord\Support\VueProp;
use Fjord\Crud\Models\FormBlock;
use Fjord\Vue\Crud\PreviewTable;
use Fjord\Crud\Fields\Blocks\Blocks;

class Repeatables extends VueProp
{
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
     * @param Blocks $field
     */
    public function __construct(Blocks $field)
    {
        $this->routePrefix = strip_slashes("{$field->route_prefix}/blocks/{$field->id}/{block_id}");
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
     * To array.
     *
     * @return array
     */
    public function getArray(): array
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
        return $this->forms[$key];
    }
}
