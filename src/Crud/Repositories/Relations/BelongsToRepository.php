<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudBaseController;
use Ignite\Crud\Fields\Relations\BelongsTo;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;

class BelongsToRepository extends RelationRepository
{
    use Concerns\ManagesRelated;

    /**
     * BelongsTo field instance.
     *
     * @var BelongsTo
     */
    protected $field;

    /**
     * Create new BelongsToRepository instance.
     *
     * @param  ConfigHandler      $config
     * @param  CrudBaseController $controller
     * @param  BaseForm           $form
     * @param  BelongsTo          $field
     * @return void
     */
    public function __construct(ConfigHandler $config, CrudBaseController $controller, BaseForm $form, BelongsTo $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new belongsTo relation.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @return void
     */
    public function create(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $this->link($model, $related);
    }

    /**
     * Link two models.
     *
     * @param  Model $model
     * @param  Model $related
     * @return void
     */
    public function link(Model $model, Model $related)
    {
        $model->{$this->field->id}()->associate($related);
        $model->save();
    }

    /**
     * Destroy belongsTo relation.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        $this->getRelatedOrDelete($request, $model);

        $model->{$this->field->id}()->dissociate();
        $model->save();
    }
}
