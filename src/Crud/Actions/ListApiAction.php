<?php

use Illuminate\Http\Request;
use Fjord\Crud\Fields\ListField;
use Fjord\Crud\Actions\BaseApiAction;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Repositories\ListRepository;
use League\CommonMark\Block\Element\ListItem;

class ListApiAction extends BaseApiAction
{
    /**
     * Repository class.
     *
     * @var string
     */
    protected $repository = ListRepository::class;

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
    public function update(CrudReadRequest $request)
    {
        $this->callRepository('update', $this->field, [
            'model' => $this->getListItem($request)
        ]);
    }

    protected function destroy(Type $var = null)
    {
        # code...
    }

    /**
     * Get list item.
     *
     * @param Request $request
     * @return ListItem
     */
    protected function getListItem(Request $request)
    {
        $this->model->{$this->field->id}()
            ->findOrFail($request->list_item_id);
    }
}
