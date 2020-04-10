<?php

namespace Fjord\Form\Controllers\Traits;

use Fjord\Form\Database\FormBlock;
use Fjord\Form\Database\FormField;
use Fjord\Form\Database\FormRelation;
use Fjord\Form\Requests\FormUpdateRequest;

trait FormRelations
{
    public function orderRelation(FormUpdateRequest $request, $id, $relation)
    {
        $ids = $request->ids;

        if (!$ids) {
            abort(404);
        }

        $model = FormField::findOrFail($id);
        $formField = $model->findFormField($relation);
        if (!$formField) {
            abort(404);
        }
        $relations = $model->$relation()->get();

        foreach ($ids as $order => $id) {
            $relation = $relations->where('id', $id)->first();
            if (!$relation) {
                continue;
            }
            $relation->order_column = $order;
            $relation->save();
        }
    }

    /**
     * Delete relation.
     *
     * @param FormUpdateRequest $request
     * @param int $id
     * @param string $relation
     * @param int $relation_id
     * @return void
     */
    public function deleteRelation(FormUpdateRequest $request, $id, $relation, $relation_id)
    {
        // First we check if both crud model with the ID and relation model with
        // the ID exist, no relations should be deleted for non existing records.
        $model = FormField::findOrFail($id);
        $formField = $model->findFormField($relation);
        if (!$formField) {
            abort(404);
        }
        $relationModel = $model->$relation()->findOrFail($relation_id);

        // Delete relation for form field "relation"
        return FormRelation::where('from_model_type', get_class($model))
            ->where('from_model_id', $id)
            ->where('to_model_type', get_class($relationModel))
            ->where('to_model_id', $relation_id)
            ->delete();
    }

    /**
     * Create relation
     *
     * @param FormUpdateRequest $request
     * @param int $id
     * @param string $relation
     * @param int $relation_id
     * @return void
     */
    public function createRelation(FormUpdateRequest $request, $id, $relation, $relation_id)
    {
        // First we check if both crud model with the ID and relation model with
        // the ID exist, no relations should be created for non existing records.
        $model = FormField::findOrFail($id);
        $formField = $model->findFormField($relation);
        if (!$formField) {
            abort(404);
        }
        $relationModel = $formField->query->findOrFail($relation_id);

        // Create relation for form field "relation"
        $query = [
            'from_model_type' => FormField::class,
            'from_model_id' => $id,
            'to_model_type' => $formField->model,
            'to_model_id' => $relation_id,
        ];
        if (FormRelation::where($query)->exists()) {
            abort(422, __f("fj.already_selected"));
        }
        return FormRelation::create($query);
    }

    /**
     * Store new form_block.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function storeBlock(FormUpdateRequest $request, $id)
    {
        $model = FormField::findOrFail($id);

        $block = new FormBlock();
        $block->type = $request->type;
        $block->model_type = FormField::class;
        $block->model_id = $model->id;
        $block->field_id = $request->field_id;
        $block->value = $request->value;
        $block->order_column = $request->order_column;
        $block->save();

        return $block->eloquentJs();
    }

    /**
     * Update form_block.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function updateBlock(FormUpdateRequest $request, $id, $block_id)
    {
        $block = FormBlock::where('model_type', FormField::class)
            ->where('model_id', $id)->findOrFail($block_id);

        $block->update($request->all());

        return $block;
    }

    /**
     * Update form_block.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function destroyBlock(FormUpdateRequest $request, $id, $block_id)
    {
        $block = FormBlock::where('model_type', FormField::class)
            ->where('model_id', $id)->findOrFail($block_id);

        return $block->delete();
    }
}
