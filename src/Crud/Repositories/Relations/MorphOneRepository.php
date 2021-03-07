<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Crud\Fields\Relations\MorphOne;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;

class MorphOneRepository extends RelationRepository
{
    use Concerns\ManagesRelated;

    /**
     * MorphOne field instance.
     *
     * @var MorphOne
     */
    protected $field;

    /**
     * Create new MorphManyRepository instance.
     *
     * @return void
     */
    public function __construct($config, $controller, $form, MorphOne $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new MorphOne relation.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
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
        $morphOne = $this->field->getRelationQuery($model);

        $query = [
            $morphOne->getMorphType()      => get_class($model),
            $morphOne->getForeignKeyName() => $model->{$morphOne->getLocalKeyName()},
        ];

        // Remove existsing morphOne relations.
        $morphOne->where($query)
            ->where('id', '!=', $related->id)
            ->update([
                $morphOne->getMorphType()      => '',
                $morphOne->getForeignKeyName() => 0,
            ]);

        $related->update($query);
    }

    /**
     * Remove MorphOne relation.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        if (! $related = $this->getRelatedOrDelete($request, $model)) {
            return;
        }

        $morphOne = $this->field->getRelationQuery($model);

        $related->{$morphOne->getMorphType()} = '';
        $related->{$morphOne->getForeignKeyName()} = 0;
        $related->update();
    }
}
