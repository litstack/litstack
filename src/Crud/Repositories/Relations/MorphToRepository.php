<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudBaseController;
use Ignite\Crud\Fields\Relations\MorphOne;
use Ignite\Crud\Fields\Relations\MorphTo;
use Ignite\Crud\Fields\Relations\MorphToRegistrar;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;

class MorphToRepository extends RelationRepository
{
    use Concerns\ManagesRelated;

    /**
     * MorphOne field instance.
     *
     * @var MorphToRegistrar
     */
    protected $field;

    /**
     * Create new MorphManyRepository instance.
     *
     * @return void
     */

    /**
     * Create new MorphManyRepository instance.
     *
     * @param  ConfigHandler      $config
     * @param  CrudBaseController $controller
     * @param  BaseForm           $form
     * @param  MorphTo            $field
     * @return void
     */
    public function __construct($config, $controller, $form, MorphTo $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new MorphOne relation.
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
        $morphTo = $this->field->getRelationQuery($model);

        $model->{$morphTo->getMorphType()} = get_class($related);
        $model->{$morphTo->getForeignKeyName()} = $related->id;

        $model->save();
    }

    /**
     * Remove MorphOne relation.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        if (! $related = $this->getRelatedOrDelete($request, $model)) {
            return;
        }

        $morphTo = $this->field->getRelationQuery($model);

        $model->{$morphTo->getMorphType()} = '';
        $model->{$morphTo->getForeignKeyName()} = null;

        $model->save();
    }
}
