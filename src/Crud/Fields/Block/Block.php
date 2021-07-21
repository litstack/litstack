<?php

namespace Ignite\Crud\Fields\Block;

use Closure;
use Ignite\Crud\Fields\Traits\HasBaseField;
use Ignite\Crud\Models\LitFormModel;
use Ignite\Crud\RelationField;
use Illuminate\Support\Collection;

class Block extends RelationField
{
    use HasBaseField;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-block';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = ['title', 'repeatables'];

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function mount()
    {
        $this->blockWidth(12);
        $this->setAttribute('orderColumn', 'order_column');
        $this->confirmDelete();
    }

    /**
     * Set block width.
     *
     * @param int|float $width
     *
     * @return $this
     */
    public function blockWidth($width)
    {
        $this->setAttribute('blockWidth', $width);

        return $this;
    }

    /**
     * Confirm deleting a repeatable in a modal.
     *
     * @param bool $confirm
     * @return $this
     */
    public function confirmDelete(bool $confirm = true)
    {
        $this->setAttribute('confirm_delete', $confirm);

        return $this;
    }

    /**
     * Add repeatables.
     *
     * @param  Closure|Repeatables $closure
     * @return $this
     */
    public function repeatables($closure)
    {
        $repeatables = $closure;
        if (! $closure instanceof Repeatables) {
            $repeatables = new Repeatables($this);
            $closure($repeatables);
        }

        $this->attributes['repeatables'] = $repeatables;

        return $this;
    }

    /**
     * Check if block has repeatable.
     *
     * @param  string $name
     * @return bool
     */
    public function hasRepeatable(string $name)
    {
        return $this->repeatables->has($name);
    }

    /**
     * Check if block has repeatable.
     *
     * @param  string $name
     * @return Repeatable
     */
    public function getRepeatable($name)
    {
        return $this->repeatables->get($name);
        // if () {
        //     return $repeatable;
        // }

        // foreach ($this->getRegisteredFields() as $field) {
        //     if (! $field instanceof self) {
        //         continue;
        //     }

        //     if ($repeatable = $field->getRepeatable($name)) {
        //         return $repeatable;
        //     }
        // }
    }

    /**
     * Get relation query for model.
     *
     * @param  mixed $model
     * @param  bool  $query
     * @return mixed
     */
    public function getRelationQuery($model)
    {
        if (! $model instanceof LitFormModel) {
            return $model->{$this->id}();
        }

        return $model->repeatables($this->id);
    }

    /**
     * Get registered fields.
     *
     * @return Collection
     */
    public function getRegisteredFields()
    {
        $fields = collect([]);
        foreach ($this->repeatables->repeatables as $repeatable) {
            $fields = $fields->merge($repeatable->getRegisteredFields());
        }

        return $fields;
    }
}
