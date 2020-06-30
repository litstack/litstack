<?php

namespace Fjord\Crud\Repositories\Relations;

use Illuminate\Support\Facades\DB;
use Fjord\Crud\Fields\Relations\BelongsTo;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Repositories\BaseFieldRepository;

class BelongsToRepository extends BaseFieldRepository
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
    public function __construct($config, $controller, $form, BelongsTo $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new belongsTo relation.
     *
     * @param CrudUpdateRequest $request
     * @param mixed $model
     * @return void
     */
    public function create(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $belongsToMany = $this->field->getRelationQuery($model);

        $query = [
            $belongsToMany->getForeignPivotKeyName() => $model->{$belongsToMany->getParentKeyName()},
            $belongsToMany->getRelatedPivotKeyName() => $related->{$belongsToMany->getRelatedKeyName()}
        ];

        if ($this->field->sortable) {
            $query[$this->field->orderColumn] = $belongsToMany->count();
        }

        DB::table($belongsToMany->getTable())->insert($query);
    }

    /**
     * Destroy belongsTo relation.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed $model
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $belongsToMany = $this->field->getRelationQuery($model);
        return DB::table($belongsToMany->getTable())->where([
            $belongsToMany->getForeignPivotKeyName() => $model->{$belongsToMany->getParentKeyName()},
            $belongsToMany->getRelatedPivotKeyName() => $related->{$belongsToMany->getRelatedKeyName()}
        ])->delete();
    }
}
