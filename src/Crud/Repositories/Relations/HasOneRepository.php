<?php

namespace Lit\Crud\Repositories\Relations;

use Lit\Crud\Fields\Relations\HasOne;
use Lit\Crud\Repositories\BaseFieldRepository;
use Lit\Crud\Requests\CrudUpdateRequest;

class HasOneRepository extends BaseFieldRepository
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
        $related = $this->getRelated($request, $model);

        $hasOne = $this->field->getRelationQuery($model);

        $related->update([
            $hasOne->getForeignKeyName() => null,
        ]);
    }
}
