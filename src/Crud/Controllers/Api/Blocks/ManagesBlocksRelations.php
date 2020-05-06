<?php

namespace Fjord\Crud\Controllers\Api\Blocks;

use Fjord\Support\IndexTable;
use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesBlocksRelations
{
    /**
     * Load block relation index.
     *
     * @param CrudReadRequest $request
     * @param int $id
     * @param string $field_id
     * @param int $block_id
     * @param string $relation
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function blockRelationIndex(CrudReadRequest $request, $id, $field_id, $block_id, $relation)
    {
        $model = $this->query()->findOrFail($id);
        $blockField = $this->config->form->findField($field_id) ?? abort(404);
        $block = $blockField->relation($model, $query = true)->findOrFail($block_id);
        $field = $block->findField($relation) ?? abort(404);

        if (!$field->isRelation() || $field instanceof Blocks) {
            abort(404);
        }

        $index = IndexTable::query($field->getQuery())
            ->request($request)
            ->search($field->getRelatedConfig()->search)
            ->get();

        $index['items'] = crud($index['items']);

        return $index;
    }

    /**
     * Fetch existing relations.
     *
     * @param CrudReadRequest $request
     * @param int $id
     * @param int $field_id
     * @return void
     */
    public function loadBlockRelations(CrudReadRequest $request, $id, $field_id, $block_id, $relation)
    {
        $model = $this->query()->findOrFail($id);
        $blockField = $this->config->form->findField($field_id) ?? abort(404);
        $block = $blockField->relation($model, $query = true)->findOrFail($block_id);
        $field = $block->findField($relation) ?? abort(404);

        $this->validateRelationField($field);

        return crud(
            $field->relation($block, $query = true)->get()
        );
    }

    /**
     * Add new block relation.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param string $field_id
     * @param int $block_id
     * @param string $relation
     * @param int $relation_id
     * @return mixed
     */
    public function createBlockRelation(CrudUpdateRequest $request, $id, $field_id, $block_id, $relation, $relation_id)
    {
        $model = $this->query()->findOrFail($id);
        $blockField = $this->config->form->findField($field_id) ?? abort(404);
        $block = $blockField->relation($model, $query = true)->findOrFail($block_id);
        $field = $block->findField($relation) ?? abort(404);

        $this->validateRelationField($field);

        $relation = $field->getQuery()->findOrFail($relation_id);

        $response = $this->createFieldRelation($request, $field, $block, $relation);

        $this->edited($model, 'relation:created');

        return $response;
    }

    /**
     * Remove Block relation.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param string $field_id
     * @param int $block_id
     * @param string $relation
     * @param int $relation_id
     * @return void
     */
    public function destroyBlockRelation(CrudUpdateRequest $request, $id, $field_id, $block_id, $relation, $relation_id)
    {
        $model = $this->query()->findOrFail($id);
        $blockField = $this->config->form->findField($field_id) ?? abort(404);
        $block = $blockField->relation($model, $query = true)->findOrFail($block_id);
        $field = $block->findField($relation) ?? abort(404);

        $this->validateRelationField($field);

        $relation = $field->getQuery()->findOrFail($relation_id);

        $response = $this->destroyFieldRelation($request, $field, $block, $relation);

        $this->edited($model, 'relation:ordered');

        return $response;
    }

    /**
     * Order Block relations.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param string $relation
     * @return void
     */
    public function orderBlockRelation(CrudUpdateRequest $request, $id, $field_id, $block_id, $relation)
    {
        $ids = $request->ids ?? abort(404);
        $model = $this->query()->findOrFail($id);
        $blockField = $this->config->form->findField($field_id) ?? abort(404);
        $block = $blockField->relation($model, $query = true)->findOrFail($block_id);
        $field = $block->findField($relation) ?? abort(404);

        $this->validateRelationField($field);

        $query = $field->relation($block, $query = true);

        $response = $this->orderField($query->getQuery(), $field, $ids);

        $this->edited($model, 'relation:ordered');

        return $response;
    }
}
