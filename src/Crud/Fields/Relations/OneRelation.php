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
        'form',
        'hint',
        'preview',
        'confirm',
        'query',
        'relatedCols',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'confirm' => true,
        'relatedCols' => 12,
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

        return $model->oneRelation($this->related, $this->id)
            ->setEagerLoads(
                $this->query->getEagerLoads()
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

        $this->attributes['model'] = $model;

        if (!$this->query) {
            $this->query = $model::query();
        }

        return $this;
    }
}
