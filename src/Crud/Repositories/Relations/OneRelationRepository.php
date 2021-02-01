<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Crud\Fields\Relations\OneRelationField;
use Ignite\Crud\Models\Relation;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;

class OneRelationRepository extends RelationRepository
{
    use Concerns\ManagesRelated;

    /**
     * Create new OneRelationRepository instance.
     */
    public function __construct($config, $controller, $form, OneRelationField $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new oneRelation.
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
        $query = [
            'from_model_type' => get_class($model),
            'from_model_id'   => $model->id,
            'to_model_type'   => get_class($related),
            'field_id'        => $this->field->id,
        ];

        // Replace previous relation with new one.
        Relation::where($query)->delete();
        $query['to_model_id'] = $related->id;
        Relation::create($query);
    }

    /**
     * Destroy oneRelation.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     *
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $query = [
            'from_model_type' => get_class($model),
            'from_model_id'   => $model->id,
            'to_model_type'   => get_class($related),
            'to_model_id'     => $related->id,
            'field_id'        => $this->field->id,
        ];

        Relation::where($query)->delete();

        $this->deleteIfDesired($request, $related);
    }
}
