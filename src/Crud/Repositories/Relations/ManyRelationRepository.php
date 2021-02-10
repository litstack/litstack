<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Crud\Fields\Relations\ManyRelationField;
use Ignite\Crud\Models\Relation;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;

class ManyRelationRepository extends RelationRepository
{
    use Concerns\ManagesRelated;

    /**
     * ManyRelationField field instance.
     *
     * @var ManyRelationField
     */
    protected $field;

    /**
     * Create new ManyRelationRepository instance.
     */
    public function __construct($config, $controller, $form, ManyRelationField $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new manyRelation.
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
        $order_column = $this->field->getRelationQuery($model)->count();

        $query = [
            'from_model_type' => get_class($model),
            'from_model_id'   => $model->id,
            'to_model_type'   => get_class($related),
            'to_model_id'     => $related->id,
            'field_id'        => $this->field->id,
            'order_column'    => $order_column,
        ];

        // Check if relation already exists.
        if (Relation::where($query)->exists()) {
            abort(404);
        }

        Relation::create($query);
    }

    /**
     * Destroy manyRelation.
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
