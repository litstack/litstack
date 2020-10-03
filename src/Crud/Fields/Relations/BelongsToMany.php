<?php

namespace Ignite\Crud\Fields\Relations;

use Closure;
use Ignite\Crud\Fields\Traits\HasBaseField;
use Illuminate\Database\Eloquent\Model;

class BelongsToMany extends ManyRelationField
{
    use HasBaseField;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'belongsToMany',
    ];

    /**
     * Attaching callback.
     *
     * @var Closure|null
     */
    protected $attachingCallback;

    /**
     * Fill pivot attributes.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function withPivotAttributes(Closure $closure)
    {
        $this->attachingCallback = $closure;

        return $this;
    }

    /**
     * Get attributes to be attached.
     *
     * @param  Model $model
     * @param  Model $related
     * @return array
     */
    public function getPivotAttributes(Model $model, Model $related): array
    {
        if (! $this->attachingCallback instanceof Closure) {
            return [];
        }

        return call_user_func($this->attachingCallback, $model, $related);
    }
}
