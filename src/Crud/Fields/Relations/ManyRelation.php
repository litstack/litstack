<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\ManyRelationField;

class ManyRelation extends ManyRelationField
{
    use Concerns\ManagesRelation;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'manyRelation'
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
        'hint',
        'edit',
        'preview',
        'confirm',
        'sortable',
        'query'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'confirm' => false,
        'sortable' => true,
        'orderColumn' => 'order_column'
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

        return $model->manyRelation($this->related, $this->id)
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
