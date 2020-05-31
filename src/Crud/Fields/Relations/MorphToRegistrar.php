<?php

namespace Fjord\Crud\Fields\Relations;

use Closure;
use Fjord\Exceptions\InvalidArgumentException;
use Fjord\Crud\Fields\Concerns\FormItemWrapper;

class MorphToRegistrar extends OneRelationField
{
    use FormItemWrapper;

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
    public $requiredAttributes = [
        'morphTypes'
    ];

    /**
     * Available field attributes.
     *
     * @var array
     */
    public $availableAttributes = [
        'form',
        'morphTypes'
    ];

    /**
     * Default field attributes.
     *
     * @var array
     */
    public $defaultAttributes = [];

    /**
     * Should field be registered in form.
     *
     * @return boolean
     */
    public function register()
    {
        return false;
    }

    /**
     * Add morph types.
     *
     * @param Closure $callback
     * @return self
     */
    public function morphTypes(Closure $closure)
    {
        if (!array_key_exists('title', $this->attributes)) {
            throw new InvalidArgumentException('You may set a title before defining morph types.', [
                'function' => 'types'
            ]);
        }

        $morph = new MorphTypeManager;
        $closure($morph);

        $this->setAttribute('morphTypes', $morph);

        $options = [];
        foreach ($morph->getTypes() as $class => $morphType) {
            $options[$class] = $morphType['name'];
        }

        $selectId = (new $this->model)->{$this->id}()->getMorphType();
        $this->formInstance->select($selectId)
            ->title(__f('base.item_select', ['item' => $this->title]))
            ->options($options)
            ->storable(false);

        foreach ($morph->getTypes() as $class => $morphType) {
            $idDivider = MorphTo::ID_DIVIDER;
            $morphId = "{$this->id}{$idDivider}{$class}";
            $field = $this->formInstance->registerField(MorphTo::class, $morphId);
            foreach ($this->attributes as $key => $value) {
                if (!in_array($key, $this->availableAttributes)) {
                    continue;
                }
                $field->{$key} = $value;
            }
            $field->title($morphType['name'])
                ->dependsOn($selectId, $class);
            $field->setAttribute('preview', $morphType['preview']);

            $this->morphTypes[$class] = $field;
        }
    }

    /**
     * Add edit form.
     *
     * @param Closure $closure
     * @return void
     */
    public function form(Closure $closure)
    {
        throw new InvalidArgumentException('form is not available for MorphTo relations.');
    }

    /**
     * Build relation index table.
     *
     * @param Closure $closure
     * @return void
     */
    public function preview(Closure $closure)
    {
        throw new InvalidArgumentException('form is not available for MorphTo relations.');
    }
}
