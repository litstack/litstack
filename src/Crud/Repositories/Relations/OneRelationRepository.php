<?php

namespace Fjord\Crud\Repositories\Relations;

use Fjord\Crud\Models\FormRelation;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Repositories\BaseFieldRepository;
use Fjord\Crud\Fields\Relations\OneRelationField;

class OneRelationRepository extends BaseFieldRepository
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
     * @param mixed $model
     * @return void
     */
    public function create(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $query = [
            'from_model_type' => get_class($model),
            'from_model_id' => $model->id,
            'to_model_type' => get_class($related),
            'field_id' => $this->field->id
        ];

        // Replace previous relation with new one.
        FormRelation::where($query)->delete();
        $query['to_model_id'] = $related->id;
        FormRelation::create($query);
    }

    /**
     * Destroy oneRelation.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed $model
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $query = [
            'from_model_type' => get_class($model),
            'from_model_id' => $model->id,
            'to_model_type' => get_class($related),
            'to_model_id' => $related->id,
            'field_id' => $this->field->id
        ];

        FormRelation::where($query)->delete();
    }
}
