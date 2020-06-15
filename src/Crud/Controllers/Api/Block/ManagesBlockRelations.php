<?php

namespace Fjord\Crud\Controllers\Api\Block;

use Fjord\Support\IndexTable;
use Fjord\Crud\Fields\ListField;
use Fjord\Crud\Fields\Block\Block;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Fields\Relations\LaravelRelationField;

trait ManagesBlockRelations
{
    /**
     * Load block relation index.
     *
     * @param CrudReadRequest $request
     * @param string|integer $identifier
     * @param string $form_name
     * @param string $field_id
     * @param int $block_id
     * @param string $relation
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function blockRelationIndex(CrudReadRequest $request, $identifier, $form_name, $field_id, $block_id, $relation)
    {
        $this->formExists($form_name) ?: abort(404);
        $blockField = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        ($blockField instanceof Block || $blockField instanceof ListField) ?: abort(404);
        // Find model.
        $model = $this->findOrFail($identifier);
        // Find block.
        $block = $blockField->getRelationQuery($model)->findOrFail($block_id);
        // Find relation field.
        $relationField = $block->findField($relation) ?? abort(404);

        if (!$relationField instanceof LaravelRelationField) {
            abort(404);
        }

        $index = IndexTable::query($relationField->getQuery())
            ->request($request)
            ->search($relationField->getRelatedConfig()->search)
            ->get();

        $index['items'] = crud($index['items']);

        return $index;
    }

    /**
     * Fetch existing relations.
     *
     * @param CrudReadRequest $request
     * @param string|integer $identifier
     * @param string $form_name
     * @param int $field_id
     * @return void
     */
    public function loadBlockRelations(CrudReadRequest $request, $identifier, $form_name, $field_id, $block_id, $relation)
    {
        $this->formExists($form_name) ?: abort(404);
        $blockField = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        ($blockField instanceof Block || $blockField instanceof ListField) ?: abort(404);
        // Find model.
        $model = $this->findOrFail($identifier);
        // Find block.
        $block = $blockField->getRelationQuery($model)->findOrFail($block_id);
        // Find relation field.
        $relationField = $block->findField($relation) ?? abort(404);

        $this->validateRelationField($relationField);

        $query = $relationField->getRelationQuery($block);

        $relations = IndexTable::query($query)
            ->request($request)
            ->search($relationField->getRelatedConfig()->search)
            ->get();

        $relations['items'] = crud(
            $relations['items']
        );

        return $relations;
    }

    /**
     * Add new block relation.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $identifier
     * @param string $form_name
     * @param string $field_id
     * @param int $block_id
     * @param string $relation
     * @param int $relation_id
     * @return mixed
     */
    public function createBlockRelation(CrudUpdateRequest $request, $identifier, $form_name, $field_id, $block_id, $relation, $relation_id)
    {
        $this->formExists($form_name) ?: abort(404);
        $blockField = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        ($blockField instanceof Block || $blockField instanceof ListField) ?: abort(404);
        // Find model.
        $model = $this->findOrFail($identifier);
        // Find block.
        $block = $blockField->getRelationQuery($model)->findOrFail($block_id);
        // Find relation field.
        $relationField = $block->findField($relation) ?? abort(404);

        $this->validateRelationField($relationField);

        $relation = $relationField->getQuery()->findOrFail($relation_id);

        $response = $this->createFieldRelation($request, $relationField, $block, $relation);

        $this->edited($model, 'relation:created');

        return $response;
    }

    /**
     * Remove Block relation.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $identifier
     * @param string $form_name
     * @param string $field_id
     * @param int $block_id
     * @param string $relation
     * @param int $relation_id
     * @return void
     */
    public function destroyBlockRelation(CrudUpdateRequest $request, $identifier, $form_name, $field_id, $block_id, $relation, $relation_id)
    {
        $this->formExists($form_name) ?: abort(404);
        $blockField = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        ($blockField instanceof Block || $blockField instanceof ListField) ?: abort(404);
        // Find model.
        $model = $this->findOrFail($identifier);
        // Find block.
        $block = $blockField->getRelationQuery($model)->findOrFail($block_id);
        // Find relation field.
        $relationField = $block->findField($relation) ?? abort(404);

        $this->validateRelationField($relationField);

        $relation = $relationField->getQuery()->findOrFail($relation_id);

        $response = $this->destroyFieldRelation($request, $relationField, $block, $relation);

        $this->edited($model, 'relation:ordered');

        return $response;
    }

    /**
     * Order Block relations.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $identifier
     * @param string $form_name
     * @param string $relation
     * @return void
     */
    public function orderBlockRelation(CrudUpdateRequest $request, $identifier, $form_name, $field_id, $block_id, $relation)
    {
        $ids = $request->ids ?? abort(404);
        $this->formExists($form_name) ?: abort(404);
        $blockField = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        ($blockField instanceof Block || $blockField instanceof ListField) ?: abort(404);
        // Find model.
        $model = $this->findOrFail($identifier);
        // Find block.
        $block = $blockField->getRelationQuery($model)->findOrFail($block_id);
        // Find relation field.
        $relationField = $block->findField($relation) ?? abort(404);

        $this->validateRelationField($relationField);

        $query = $relationField->getRelationQuery($block);

        $response = $this->orderField($query, $relationField, $ids);

        $this->edited($model, 'relation:ordered');

        return $response;
    }
}
