<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Crud\Fields\Relations\HasOne;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;

class HasOneRepository extends RelationRepository
{
    use Concerns\ManagesRelated;

    /**
     * HasOne field instance.
     *
     * @var HasOne
     */
    protected $field;

    /**
     * Create new HasOneRepository instance.
     */
    public function __construct($config, $controller, $form, HasOne $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new hasOne relation.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     *
     * @return void
     */
    public function create(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $this->link($model, $related);
    }

    /**
     * Link two models.
     *
     * @param  Model $model
     * @param  Model $related
     * @return void
     */
    public function link(Model $model, Model $related)
    {
        $hasOne = $this->field->getRelationQuery($model);

        $query = [
            $hasOne->getForeignKeyName() => $model->{$hasOne->getLocalKeyName()},
        ];

        // Remove existing hasOne relations.
        $hasOne->where($query)->update([
            $hasOne->getForeignKeyName() => null,
        ]);

        // Create new relation.
        $related->update($query);
    }

    /**
     * Remove hasOne relation.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     *
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        if (! $related = $this->getRelatedOrDelete($request, $model)) {
            return;
        }

        $hasOne = $this->field->getRelationQuery($model);

        $related->update([
            $hasOne->getForeignKeyName() => null,
        ]);
    }
}
