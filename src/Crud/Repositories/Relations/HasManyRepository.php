<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Crud\Fields\Relations\HasMany;
use Ignite\Crud\Repositories\BaseFieldRepository;
use Ignite\Crud\Requests\CrudUpdateRequest;

class HasManyRepository extends BaseFieldRepository
{
    use Concerns\ManagesRelated;

    /**
     * HasMany field instance.
     *
     * @var HasMany
     */
    protected $field;

    /**
     * Create new HasManyRepository instance.
     */
    public function __construct($config, $controller, $form, HasMany $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new hasMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     *
     * @return void
     */
    public function create(CrudUpdateRequest $request, $model)
    {
        $this->checkMaxItems($model);

        $related = $this->getRelated($request, $model);

        $hasMany = $this->field->getRelationQuery($model);

        $related->{$hasMany->getForeignKeyName()} = $model->{$hasMany->getLocalKeyName()};

        // Sortable
        if ($this->field->sortable) {
            $related->{$this->field->orderColumn} = $hasMany->count();
        }

        $related->update();
    }

    /**
     * Remove hasMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     *
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $hasMany = $this->field->getRelationQuery($model);

        $related->update([
            $hasMany->getForeignKeyName() => null,
        ]);
    }
}
