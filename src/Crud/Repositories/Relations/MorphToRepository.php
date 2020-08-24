<?php

namespace Lit\Crud\Repositories\Relations;

use Lit\Crud\BaseForm;
use Lit\Crud\Controllers\CrudBaseController;
use Lit\Crud\Fields\Relations\MorphOne;
use Lit\Crud\Fields\Relations\MorphTo;
use Lit\Crud\Fields\Relations\MorphToRegistrar;
use Lit\Crud\Repositories\BaseFieldRepository;
use Lit\Crud\Requests\CrudUpdateRequest;

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
