<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Crud\Fields\Relations\BelongsTo;
use Ignite\Crud\Fields\Relations\BelongsToMany;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;

class BelongsToManyRepository extends RelationRepository
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
        $attributes = $this->field->getPivotAttributes($model, $related);
        if ($this->field->sortable) {
            $attributes[$this->field->orderColumn] = $this->field->getRelationQuery($model)->count();
        }

        $model->{$this->field->id}()->attach($related->getKey(), $attributes);
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
        if (! $related = $this->getRelatedOrDelete($request, $model)) {
            return;
        }

        $model->{$this->field->id}()->detach($related->getKey());
    }
}
