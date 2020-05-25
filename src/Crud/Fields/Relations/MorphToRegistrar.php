<?php

namespace Fjord\Crud\Fields\Relations;

use Closure;
use Fjord\Crud\OneRelationField;
use Fjord\Exceptions\InvalidArgumentException;


class MorphToRegistrar extends OneRelationField
{
    use Concerns\ManagesRelation;

    /**
     * MorphTypes.
     *
     * @var array
     */
    protected $morphTypes = [];

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
        'model',
        'types'
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'model',
        'hint',
        'form',
        'previewQuery',
        'preview',
        'confirm',
        'query',
        'relatedCols',
        'small',
        'types'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [];

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
    public function types(Closure $closure)
    {
        if (!array_key_exists('title', $this->attributes)) {
            throw new InvalidArgumentException('You may set a title before defining morph types.', [
                'function' => 'types'
            ]);
        }

        $morph = new MorphTypeManager;
        $closure($morph);

        $this->setAttribute('types', $morph);

        $options = [];
        foreach ($morph->getTypes() as $class => $morphType) {
            $options[$class] = $morphType['name'];
        }

        $selectId = (new $this->model)->{$this->id}()->getMorphType();
        $this->form->select($selectId)
            ->title(__f('base.item_select', ['item' => $this->title]))
            ->options($options)
            ->storable(false);

        foreach ($morph->getTypes() as $class => $morphType) {
            $idDivider = MorphTo::ID_DIVIDER;
            $morphId = "{$this->id}{$idDivider}{$class}";
            $field = $this->form->registerField(MorphTo::class, $morphId);
            foreach ($this->attributes as $key => $value) {
                if (!in_array($key, $this->available)) {
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
