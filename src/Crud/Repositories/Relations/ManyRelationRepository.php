<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Crud\Fields\Relations\ManyRelationField;
use Ignite\Crud\Models\Relation;
use Ignite\Crud\Repositories\BaseFieldRepository;
use Ignite\Crud\Requests\CrudUpdateRequest;

class ManyRelationRepository extends BaseFieldRepository
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

        $order_column = $this->field->getRelationQuery($model)->count();

        $query = [
            'from_model_type' => get_class($model),
            'from_model_id'   => $model->id,
            'to_model_type'   => get_class($related),
            'to_model_id'     => $related->id,
            'field_id'        => $this->field->id,
            'order_column'    => $order_column,
        ];

        $class = config('lit.models.relation');

        // Check if relation already exists.
        if ($class::where($query)->exists()) {
            abort(404);
        }

        $class::create($query);
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

        $class = config('lit.models.relation');

        $class::where($query)->delete();

        $this->deleteIfDesired($request, $related);
    }
}
