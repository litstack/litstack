<?php

namespace Ignite\Crud\Repositories;

use Ignite\Crud\CrudValidator;
use Ignite\Crud\Fields\ListField\ListField;
use Ignite\Crud\Models\ListItem;
use Ignite\Crud\Requests\CrudReadRequest;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Http\Request;

class ListRepository extends BaseFieldRepository
{
    /**
     * Create new ListRepository instance.
     */
    public function __construct($config, $controller, $form, ListField $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Load list items for model.
     *
     * @param  CrudReadRequest $request
     * @param  mixed           $model
     * @return CrudJs
     */
    public function index(CrudReadRequest $request, $model)
    {
        $items = $this->field->getRelationQuery($model)->getFlat()->filter(function (ListItem $item) {
            return $this->field->itemAuthorized($item, 'read');
        });

        return crud($items, $this->field);
    }

    /**
     * Update list item.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @param  object            $payload
     * @return CrudJs
     */
    public function update(CrudUpdateRequest $request, $model, $payload)
    {
        CrudValidator::validate(
            (array) $payload,
            $this->field->form,
            CrudValidator::UPDATE
        );

        $attributes = $this->formatAttributes((array) $payload, $this->field->getRegisteredFields());

        $listItem = $this->getListItem($model, $request->list_item_id);

        $this->checkAuthorized($listItem, 'update');

        $listItem->update($attributes);

        return crud($listItem, $this->field);
    }

    /**
     * Send new list item model.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @param  object            $payload
     * @return CrudJs
     */
    public function create(CrudUpdateRequest $request, $model, $payload)
    {
        $parent = $this->getParent($model, $payload->parent_id ?? 0);

        $this->checkAuthorized($parent, 'create');

        $newDepth = ($parent->depth ?? 0) + 1;
        $this->checkMaxDepth($newDepth, $this->field->maxDepth);

        $listItem = new ListItem([
            'parent_id'   => $parent->id ?? 0,
            'config_type' => get_class($this->config->getConfig()),
            'form_type'   => $request->form_type ?? 'show',
        ]);

        return crud($listItem, $this->field);
    }

    /**
     * Store new list item to database.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @param  object            $payload
     * @return CrudJs
     */
    public function store(CrudUpdateRequest $request, $model, $payload)
    {
        $parent = $this->getParent($model, $request->parent_id ?? 0);

        $this->checkAuthorized($parent, 'create');

        if ($request->parent_id && ! $parent) {
            abort(404, debug("Couldn't find parent list item wit id [{$request->parent_id}]."));
        }

        if ($parent) {
            $this->checkMaxDepth($parent->depth + 1, $this->field->maxDepth);
        }

        CrudValidator::validate(
            (array) $payload,
            $this->field->form,
            CrudValidator::CREATION
        );

        $order_column = ListItem::where([
            'config_type' => $this->config->getNamespace(),
            'form_type'   => $payload->form_type ?? 'show',
            'model_type'  => get_class($model),
            'model_id'    => $model->id,
            'field_id'    => $this->field->id,
            'parent_id'   => $parent->id ?? 0,
        ])->count();

        $listItem = new ListItem();
        $listItem->model_type = get_class($model);
        $listItem->model_id = $model->id;
        $listItem->field_id = $this->field->id;
        $listItem->config_type = get_class($this->config->getConfig());
        $listItem->form_type = $payload->form_type ?? 'show';
        $listItem->parent_id = $parent->id ?? 0;
        $listItem->order_column = $order_column;
        $listItem->value = (object) [];
        $listItem->save();

        $listItem->update((array) $payload);

        return crud($listItem, $this->field);
    }

    /**
     * Destory list item.
     *
     * @param  CrudReadRequest $request
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model, $payload)
    {
        $this->getListItem($model, $payload->list_item_id ?? 0)->delete();
    }

    /**
     * Order list.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @param  object            $payload
     * @return void
     */
    public function order(CrudUpdateRequest $request, $model, $payload)
    {
        $request->validate([
            'payload.items'                => 'required',
            'payload.items.*.order_column' => 'required|integer',
            'payload.items.*.id'           => 'required|integer',
            'payload.items.*.parent_id'    => 'integer',
        ], __lit('validation'));

        $orderedItems = $payload->items;
        $listItems = $this->field->getRelationQuery($model)->getFlat();

        foreach ($orderedItems as $orderedItem) {
            $parentId = $orderedItem['parent_id'] ?? null;

            if (! $parentId) {
                continue;
            }

            if (! $parent = $listItems->find($parentId)) {
                abort(405);
            }

            $this->checkMaxDepth($parent->depth + 1, $this->field->maxDepth);
        }

        foreach ($orderedItems as $orderedItem) {
            $update = [
                'order_column' => $orderedItem['order_column'],
            ];
            if (array_key_exists('parent_id', $orderedItem)) {
                $update['parent_id'] = $orderedItem['parent_id'];
            }
            $this->field->getRelationQuery($model)
                ->where('id', $orderedItem['id'])
                ->update($update);
        }
    }

    /**
     * Get child field.
     *
     * @param  string     $field_id
     * @return Field|null
     */
    public function getField($field_id)
    {
        return $this->field->form->findField($field_id);
    }

    /**
     * Get list item model.
     *
     * @param  Request  $request
     * @param  mixed    $model
     * @return ListItem
     */
    public function getModel(Request $request, $model)
    {
        return $this->getListItem($model, $request->list_item_id);
    }

    /**
     * Get list item.
     *
     * @param  string|int $id
     * @return ListItem
     */
    protected function getListItem($model, $id)
    {
        return $model->{$this->field->id}()->findOrFail($id);
    }

    /**
     * Get parent by id.
     *
     * @param  string|int $parentId
     * @return ListItem
     */
    protected function getParent($model, $parentId = 0)
    {
        return $this->field->getRelationQuery($model)->find($parentId);
        // ->getFlat()
        // ->find($parentId);
    }

    /**
     * Check max depth.
     *
     * @param  int  $depth
     * @param  int  $maxDepth
     * @return void
     */
    protected function checkMaxDepth(int $depth, int $maxDepth)
    {
        if ($depth <= $maxDepth) {
            return;
        }

        return abort(405, __lit('crud.fields.list.messages.max_depth', [
            'count' => $maxDepth,
        ]));
    }

    /**
     * Checks authorized.
     *
     * @param  ListItem $item
     * @return void
     */
    protected function checkAuthorized(ListItem $item = null, $operation)
    {
        if (! $this->field->itemAuthorized($item, $operation)) {
            abort(405, __lit('base.unauthorized'));
        }
    }
}
