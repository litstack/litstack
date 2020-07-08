<?php

namespace Fjord\Crud\Repositories\Relations;

use Fjord\Crud\Fields\Relations\ManyRelationField;
use Fjord\Crud\Models\FormRelation;
use Fjord\Crud\Repositories\BaseFieldRepository;
use Fjord\Crud\Requests\CrudUpdateRequest;

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

        // Check if relation already exists.
        if (FormRelation::where($query)->exists()) {
            abort(404);
        }

        FormRelation::create($query);
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

        FormRelation::where($query)->delete();
    }
}
