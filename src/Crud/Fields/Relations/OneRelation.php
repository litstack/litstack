<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\Models\FormField;
use Fjord\Crud\OneRelationField;

class OneRelation extends OneRelationField
{
    use Concerns\ManagesRelation;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'oneRelation'
    ];

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
        'model',
        'preview',
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'model',
        'form',
        'hint',
        'previewQuery',
        'preview',
        'confirm',
        'query',
        'relatedCols',
        'small',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'confirm' => true,
        'relatedCols' => 12,
        'small' => false,
        'perPage' => 1,
        'searchable' => false,
    ];

    /**
     * Get relation for model.
     *
     * @param mixed $model
     * @param boolean $query
     * @return mixed
     */
    protected function getRelation($model)
    {
        if (method_exists($model, $this->id)) {
            return parent::getRelation($model);
        }

        return $this->modifyQuery(
            $model->oneRelation($this->related, $this->id)
        );
    }

    /**
     * Set related model.
     *
     * @param string $mode
     * @return void
     */
    public function model(string $model)
    {
        $this->related = $model;

        $this->loadRelatedConfig($model);
        $this->setRelation();

        $this->attributes['model'] = $model;

        if (!$this->query) {
            $this->query = $model::query();
        }

        return $this;
    }
}
