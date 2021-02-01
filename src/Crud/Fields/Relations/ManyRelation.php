<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\Fields\Traits\HasBaseField;
use Ignite\Crud\Repositories\Relations\ManyRelationRepository;

class ManyRelation extends ManyRelationField
{
    use Concerns\ManagesLitRelationField;
    use HasBaseField;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'manyRelation',
    ];

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = ['model'];

    /**
     * Repository class.
     *
     * @var string
     */
    protected $repository = ManyRelationRepository::class;

    /**
     * Get relation for model.
     *
     * @param mixed $model
     * @param bool  $query
     *
     * @return mixed
     */
    public function getRelationQuery($model)
    {
        if (method_exists($model, $this->id)) {
            return parent::getRelationQuery($model);
        }

        return $this->modifyQuery(
            $model->manyRelation($this->getRelatedModelClass(), $this->id)
        );
    }
}
