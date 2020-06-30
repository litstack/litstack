<?php

namespace Fjord\Crud\Fields\Block;

use Closure;
use Fjord\Crud\RelationField;
use Fjord\Crud\Models\FormField;
use Fjord\Crud\Fields\Traits\HasBaseField;

class Block extends RelationField
{
    use HasBaseField;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-block';

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
        $this->blockWidth(12);
        $this->setAttribute('orderColumn', 'order_column');
    }

    /**
     * Set block width.
     *
     * @param integer|float $width
     * @return $this
     */
    public function blockWidth($width)
    {
        $this->setAttribute('blockWidth', $width);

        return $this;
    }

    /**
     * Add repeatables.
     *
     * @param Closure|Repeatables $closure
     * @return $this
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
     * Check if block has repeatable.
     *
     * @param string $name
     * @return boolean
     */
    public function hasRepeatable(string $name)
    {
        return $this->repeatables->has($name);
    }

    /**
     * Check if block has repeatable.
     *
     * @param string $name
     * @return boolean
     */
    public function getRepeatable($name)
    {
        return $this->repeatables->get($name);
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
            return $model->{$this->id}();
        }

        return $model->repeatables($this->id);
    }
}
