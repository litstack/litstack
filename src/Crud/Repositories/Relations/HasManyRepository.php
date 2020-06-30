<?php

namespace Fjord\Crud\Repositories\Relations;

use Illuminate\Support\Facades\DB;
use Fjord\Crud\Fields\Relations\HasMany;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Repositories\BaseFieldRepository;

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
     * @param mixed $model
     * @return void
     */
    public function create(CrudUpdateRequest $request, $model)
    {
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
     * @param  CrudUpdateRequest $request
     * @param  mixed $model
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $hasMany = $this->field->getRelationQuery($model);

        $related->update([
            $hasMany->getForeignKeyName() => null
        ]);
    }
}
