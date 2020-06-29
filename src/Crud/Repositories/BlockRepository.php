<?php

namespace Fjord\Crud\Repositories;

use Illuminate\Http\Request;
use Fjord\Crud\CrudValidator;
use Fjord\Crud\Models\FormBlock;
use Fjord\Crud\Fields\Block\Block;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;

class BlockRepository extends BaseFieldRepository
{
    /**
     * Create new ListRepository instance.
     */
    public function __construct($config, $controller, $form, Block $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Load repeatable.
     *
     * @param CrudReadRequest $request
     * @param mixed $model
     * @return CrudJs
     */
    public function load(CrudReadRequest $request, $model)
    {
        return crud(
            $this->getRepeatable($model, $request->repeatable_id)
        );
    }

    /**
     * Fetch all repeatables.
     *
     * @param CrudReadRequest $request
     * @param mixed $model
     * @return CrudJs
     */
    public function index(CrudReadRequest $request, $model)
    {
        return crud($this->field->getResults($model));
    }

    /**
     * Destroy repeatable.
     *
     * @param CrudUpdateRequest $request
     * @param mixed $model
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        $this->getRepeatable($model, $request->repeatable_id ?? 0)->delete();
    }

    /**
     * Update repeatable.
     *
     * @param CrudUpdateRequest $request
     * @param mixed $model
     * @param object $payload
     * @return CrudJs
     */
    public function update(CrudUpdateRequest $request, $model, $payload)
    {
        $type = $request->type;
        $repeatable = $this->field->getRepeatable($type) ?:
            abort(404, debug("Repeatable [{$type}] not found for block field [{$this->field->id}]"));

        CrudValidator::validate(
            (array) $payload,
            $repeatable->getForm(),
            CrudValidator::UPDATE
        );

        $attributes = $this->formatAttributes((array) $payload, $repeatable->getRegisteredFields());

        $repeatableModel = $this->getRepeatable($model, $request->repeatable_id ?? 0);
        $repeatableModel->update($attributes);

        return crud($repeatable);
    }

    /**
     * Store new repeatable in database.
     *
     * @param CrudUpdateRequest $request
     * @param mixed $model
     * @param object $payload
     * @return CrudJs
     */
    public function store(CrudUpdateRequest $request, $model, $payload)
    {
        $type = $payload->type ?? null;
        $this->field->hasRepeatable($type)
            ?: abort(404, debug("Repeatable [{$type}] not found on block [{$this->field->id}]"));

        $order_column = $this->getOrderColumnForNewRepeatable($request, $model, $type);

        $block = new FormBlock();
        $block->type = $type;
        $block->model_type = get_class($model);
        $block->model_id = $model->id;
        $block->field_id = $this->field->id;
        $block->config_type = get_class($this->config->getConfig());
        $block->form_type = $request->form_type;
        $block->order_column = $order_column;
        $block->save();

        return crud($block);
    }

    /**
     * Update repeatable order.
     *
     * @param CrudUpdateRequest $request
     * @param mixed $model
     * @param object $payload
     * @return void
     */
    public function order(CrudUpdateRequest $request, $model, $payload)
    {
        validator()->validate((array) $payload, [
            'ids' => 'required|array'
        ], __f('validation'));

        $query = $this->field->getRelationQuery($model);

        $order = $this->orderField($query, $this->field, $payload->ids);

        $this->edited($model, 'relation:ordered');

        return $order;
    }

    /**
     * Get order column for new repeatable.
     *
     * @param Request $request
     * @param mixed $model
     * @param string $type
     * @return integer
     */
    protected function getOrderColumnForNewRepeatable(Request $request, $model, $type)
    {
        return FormBlock::where([
            'type' => $type,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'field_id' => $this->field->id,
            'config_type' => get_class($this->config->getConfig()),
        ])->count();
    }

    /**
     * Get repeatable.
     *
     * @param mixed $model
     * @param integer|string $id
     * @return FormBlock
     */
    protected function getRepeatable($model, $id)
    {
        return $model->{$this->field->id}()->findOrFail($id);
    }
}
