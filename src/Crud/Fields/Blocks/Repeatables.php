<?php

namespace Fjord\Crud\Fields\Blocks;

use Closure;
use Fjord\Crud\Form;
use Fjord\Crud\Models\FormBlock;
use Fjord\Application\Config\ConfigItem;

class Repeatables extends ConfigItem
{
    /**
     * Registered forms.
     *
     * @var array
     */
    protected $forms = [];

    /**
     * Undocumented function
     *
     * @param string $name
     * @param Closure $closure
     * @return void
     */
    public function add(string $name, Closure $closure)
    {
        $form = new Form(FormBlock::class);

        $closure($form);

        $this->forms[$name] = $form;

        return $this;
    }

    /**
     * To array.
     *
     * @return array
     */
    public function toArray()
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
