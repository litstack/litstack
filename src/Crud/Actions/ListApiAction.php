<?php

namespace Fjord\Crud\Actions;

use Fjord\Crud\Fields\ListField;
use Fjord\Crud\Actions\BaseApiAction;
use Fjord\Crud\Requests\CrudReadRequest;
use League\CommonMark\Block\Element\ListItem;

class ListApiAction extends BaseApiAction
{
    /**
     * Required field class.
     *
     * @var string
     */
    protected $fieldClass = ListField::class;

    /**
     * Update list item.
     *
     * @param CrudReadRequest $request
     * @return mixed
     */
    // public function update(CrudReadRequest $request)
    // {
    //     $this->callRepository('update', $this->field, [
    //         'model' => $this->getListItem($request)
    //     ]);
    // }

    /**
     * Destory list item.
     *
     * @param CrudReadRequest $request
     * @return void
     */
    public function destroy(CrudReadRequest $request, $payload)
    {
        $this->getListItem($payload->list_item_id)->delete();
    }

    /**
     * Get list item.
     *
     * @param string|integer $id
     * @return ListItem
     */
    protected function getListItem($id)
    {
        return $this->model->{$this->field->id}()
            ->findOrFail($id);
    }
}
