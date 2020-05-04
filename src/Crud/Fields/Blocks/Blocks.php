<?php

namespace Fjord\Crud\Fields\Blocks;

use Closure;
use Fjord\Crud\Models\FormField;
use Fjord\Crud\ManyRelationField;

class Blocks extends ManyRelationField
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-blocks';

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
        'repeatables',
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'hint',
        'repeatables',
        'blockCols'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'blockCols' => 12
    ];

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
            $repeatables = new Repeatables($this);
            $closure($repeatables);
        }

        $this->attributes['repeatables'] = $repeatables;
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

    /**
     * Set defaults.
     *
     * @return void
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $this->attributes['orderColumn'] = 'order_column';
    }
}
