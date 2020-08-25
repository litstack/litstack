<?php

namespace Ignite\Crud\Fields\Relations;

use Closure;
use Ignite\Crud\Fields\Traits\HasBaseField;
use InvalidArgumentException;

class MorphToRegistrar extends LaravelRelationField
{
    use HasBaseField;

    /**
     * MorphTypes.
     *
     * @var array
     */
    protected $morphTypes = [];

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [
        'morphTypes',
    ];

    /**
     * Should field be registered in form.
     *
     * @return bool
     */
    public function shouldBeRegistered()
    {
        return false;
    }

    /**
     * Add morph types.
     *
     * @param Closure $callback
     *
     * @return self
     */
    public function morphTypes(Closure $closure)
    {
        $this->setAttribute('morphTypes', []);

        $selectId = (new $this->model())->{$this->id}()->getMorphType();

        $select = $this->formInstance->select($selectId)
            ->title(__lit('base.item_select', ['item' => $this->title]))
            ->storable(false);

        $morph = new MorphTypeManager($this->id, $this->formInstance, $select);

        $closure($morph);

        $options = [];
        foreach ($morph->getTypes() as $class => $morphType) {
            $options[$class] = $morphType->names['singular'];
        }

        $select->options($options);

        $this->setAttribute('morphTypes', $morph->getTypes());

        //dd($this->formInstance->getRegisteredFields());
    }

    /**
     * Build relation index table.
     *
     * @param Closure $closure
     *
     * @return void
     */
    public function preview(Closure $closure)
    {
        throw new InvalidArgumentException('Form is not available for MorphTo relations.');
    }
}
