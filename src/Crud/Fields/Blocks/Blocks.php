<?php

namespace Fjord\Crud\Fields\Blocks;

use Closure;
use Fjord\Crud\Models\FormField;
use Fjord\Crud\ManyRelationField;

class Blocks extends ManyRelationField
{
    /**
     * Field is relation.
     *
     * @var boolean
     */
    protected $relation = true;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-form-blocks';

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
        'options',
        'repeatables'
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'options',
        'hint',
        'repeatables'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Add repeatables.
     *
     * @param Closure|Repeatables $closure
     * @return void
     */
    public function repeatables($closure)
    {
        $repeatables = $closure;
        if (!$closure instanceof Repeatables) {
            $repeatables = new Repeatables;
            $closure($repeatables);
        }

        $this->attributes['repeatables'] = $repeatables;
    }

    /**
     * Cast field value.
     *
     * @param mixed $value
     * @return boolean
     */
    public function cast($value)
    {
        dd($value);
        return json_decode($value, 0);
    }

    /**
     * Get relation for model.
     *
     * @param mixed $model
     * @param boolean $query
     * @return mixed
     */
    protected function getRelation($model)
    {
        if (!$model instanceof FormField) {
            return parent::getRelation($model);
        }

        return $model->blocks($this->id);
    }
}
