<?php

namespace Lit\Crud\Repositories\Relations;

use Lit\Crud\Fields\Relations\BelongsTo;
use Lit\Crud\Fields\Relations\BelongsToMany;
use Lit\Crud\Repositories\BaseFieldRepository;
use Lit\Crud\Requests\CrudUpdateRequest;
use Illuminate\Support\Facades\DB;

class BelongsToManyRepository extends BaseFieldRepository
{
    use Concerns\ManagesRelated;

    /**
     * BelongsTo field instance.
     *
     * @var BelongsTo
     */
    protected $field;

    /**
     * Create new BelongsToRepository instance.
     */
    public function __construct($config, $controller, $form, BelongsToMany $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new belongsToMany relation.
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

        $belongsToMany = $this->field->getRelationQuery($model);

        $query = [
            $belongsToMany->getForeignPivotKeyName() => $model->{$belongsToMany->getParentKeyName()},
            $belongsToMany->getRelatedPivotKeyName() => $related->{$belongsToMany->getRelatedKeyName()},
        ];

        if ($this->field->sortable) {
            $query[$this->field->orderColumn] = $belongsToMany->count();
        }

        DB::table($belongsToMany->getTable())->insert($query);
    }

    /**
     * Destroy belongsToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     *
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $belongsToMany = $this->field->getRelationQuery($model);

        return DB::table($belongsToMany->getTable())->where([
            $belongsToMany->getForeignPivotKeyName() => $model->{$belongsToMany->getParentKeyName()},
            $belongsToMany->getRelatedPivotKeyName() => $related->{$belongsToMany->getRelatedKeyName()},
        ])->delete();
    }
}
