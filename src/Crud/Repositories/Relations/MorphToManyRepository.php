<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Crud\Fields\Relations\MorphToMany;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MorphToManyRepository extends RelationRepository
{
    use Concerns\ManagesRelated;

    /**
     * MorphToMany field instance.
     *
     * @var MorphToMany
     */
    protected $field;

    /**
     * Create new MorphManyRepository instance.
     */
    public function __construct($config, $controller, $form, MorphToMany $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new MorphToMany relation.
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
        $morphToMany = $this->field->getRelationQuery($model);

        $query = [
            $morphToMany->getRelatedPivotKeyName() => $related->{$morphToMany->getRelatedKeyName()},
            $morphToMany->getForeignPivotKeyName() => $model->{$morphToMany->getParentKeyName()},
            $morphToMany->getMorphType()           => $morphToMany->getMorphClass(),
        ];

        // Sortable
        if ($this->field->sortable) {
            $query[$this->field->orderColumn] = $morphToMany->count();
        }

        DB::table($morphToMany->getTable())->insert($query);
    }

    /**
     * Remove MorphToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     *
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $morphToMany = $this->field->getRelationQuery($model);

        DB::table($morphToMany->getTable())->where([
            $morphToMany->getRelatedPivotKeyName() => $related->{$morphToMany->getRelatedKeyName()},
            $morphToMany->getForeignPivotKeyName() => $model->{$morphToMany->getParentKeyName()},
            $morphToMany->getMorphType()           => $morphToMany->getMorphClass(),
        ])->delete();

        $this->deleteIfDesired($request, $related);
    }
}
