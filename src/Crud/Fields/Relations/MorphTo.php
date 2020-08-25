<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\Fields\Traits\HasBaseField;

class MorphTo extends OneRelationField
{
    use HasBaseField;

    /**
     * Id divider.
     */
    const ID_DIVIDER = '-';

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'morphTo',
    ];

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Create new Field instance.
     *
     * @param string      $id
     * @param string      $model
     * @param string|null $routePrefix
     */
    public function __construct(string $id, string $model, $routePrefix, $form)
    {
        $dividedId = explode(static::ID_DIVIDER, $id);
        $this->relatedModelClass = last($dividedId);
        $this->setAttribute('morphType', last($dividedId));
        $id = $dividedId[0].static::ID_DIVIDER.(new $this->morphType())->getTable();

        parent::__construct($id, $model, $routePrefix, $form);

        $this->setAttribute('local_key', $dividedId[0]);
    }

    public function getRelatedInstance()
    {
        return new $this->morphType();
    }

    /**
     * Get relation name.
     *
     * @return string
     */
    public function getRelationName()
    {
        return explode(static::ID_DIVIDER, $this->id)[0];
    }

    /**
     * Get relation.
     *
     * @param mixed $model
     *
     * @return void
     */
    public function getRelationQuery($model)
    {
        $query = $model->{$this->getRelationName()}();

        if ($model->id) {
            if ($model->{$query->getMorphType()} != $this->morphType) {
                //dd($model);
                $query->where('id', -1);
            }
        }

        return $this->modifyQuery(
            $query
        );
    }

    /**
     * Get related model instance.
     *
     * @return mixed
     */
    public function getRelated()
    {
        return new $this->morphType();
    }

    /**
     * Get results for model.
     *
     * @param mixed $model
     *
     * @return mixed
     */
    public function getResults($model)
    {
        $query = $this->relation($model);

        // Nullable morphTo.
        if (! $model->{$query->getMorphType()}) {
            return;
        }

        return $query->getResults();
    }
}
