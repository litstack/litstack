<?php

namespace Fjord\Crud\Repositories\Relations;

use Fjord\Crud\BaseForm;
use Fjord\Crud\Controllers\CrudBaseController;
use Fjord\Crud\Fields\Relations\MorphOne;
use Fjord\Crud\Fields\Relations\MorphTo;
use Fjord\Crud\Fields\Relations\MorphToRegistrar;
use Fjord\Crud\Repositories\BaseFieldRepository;
use Fjord\Crud\Requests\CrudUpdateRequest;

class MorphToRepository extends BaseFieldRepository
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
        $related = $this->getRelated($request, $model);

        $morphTo = $this->field->getRelationQuery($model);

        $model->{$morphTo->getMorphType()} = '';
        $model->{$morphTo->getForeignKeyName()} = null;

        $model->save();
    }
}
