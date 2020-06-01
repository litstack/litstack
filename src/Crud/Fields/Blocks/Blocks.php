<?php

namespace Fjord\Crud\Fields\Blocks;

use Closure;
use Fjord\Crud\RelationField;
use Fjord\Crud\Models\FormField;
use Fjord\Crud\Fields\Concerns\FormItemWrapper;

class Blocks extends RelationField
{
    use FormItemWrapper;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-blocks';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = ['title', 'repeatables'];

    /**
     * Set default field attributes
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->setAttribute('blockWidth', 12);
        $this->setAttribute('orderColumn', 'order_column');
    }

    /**
     * Add repeatables.
     *
     * @param Closure|Repeatables $closure
     * @return self
     */
    public function repeatables($closure)
    {
        $repeatables = $closure;
        if (!$closure instanceof Repeatables) {
            $repeatables = new Repeatables($this);
            $closure($repeatables);
        }

        $this->attributes['repeatables'] = $repeatables;

        return $this;
    }

    /**
     * Get relation query for model.
     *
     * @param mixed $model
     * @param boolean $query
     * @return mixed
     */
    public function getRelationQuery($model)
    {
        if (!$model instanceof FormField) {
            return parent::getRelationQuery($model);
        }

        return $model->blocks($this->id);
    }
}
