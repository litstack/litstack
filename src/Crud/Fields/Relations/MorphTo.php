<?php

namespace Fjord\Crud\Fields\Relations;

use Closure;
use Fjord\Crud\OneRelationField;
use Fjord\Exceptions\InvalidArgumentException;


class MorphTo extends OneRelationField
{
    use Concerns\ManagesRelation;

    const ID_DIVIDER = '-';

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'morphTo'
    ];

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
        'model',
        'morphType',
        'preview'
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
        'filter',
        'relatedCols',
        'small',
        'morphType'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'confirm' => false,
        'sortable' => false,
        'orderColumn' => 'order_column',
        'relatedCols' => 12,
        'small' => false,
        'perPage' => 1,
        'searchable' => false,
    ];

    /**
     * Create new Field instance.
     *
     * @param string $id
     * @param string $model
     * @param string|null $routePrefix
     */
    public function __construct(string $id, string $model, $routePrefix, $form)
    {
        parent::__construct($id, $model, $routePrefix, $form);

        $dividedId = explode(static::ID_DIVIDER, $this->id);
        $this->setAttribute('morphType', last($dividedId));
        $this->id = $dividedId[0] . static::ID_DIVIDER . (new $this->morphType)->getTable();
        $this->local_key = $dividedId[0];

        $this->initializeRelationField();
    }

    public function getRelationName()
    {
        return explode(static::ID_DIVIDER, $this->id)[0];
    }


    protected function getRelation($model)
    {
        $query = $model->{$this->getRelationName()}();

        if ($model->id) {
            if ($model->{$query->getMorphType()} != $this->morphType) {
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
        return new $this->morphType;
    }
}
