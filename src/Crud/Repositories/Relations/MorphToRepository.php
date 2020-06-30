<?php

namespace Fjord\Crud\Repositories\Relations;

use Fjord\Crud\Fields\Relations\HasOne;
use Fjord\Crud\Fields\Relations\MorphOne;
use Fjord\Crud\Fields\Relations\MorphMany;
use Fjord\Crud\Fields\Relations\MorphToRegistrar;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Repositories\BaseFieldRepository;

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
     */
    public function __construct($config, $controller, $form, MorphToRegistrar $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new MorphOne relation.
     *
     * @param CrudUpdateRequest $request
     * @param mixed $model
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
     * @param  mixed $model
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $morphTo = $this->field->getRelationQuery($model);

        $model->{$morphTo->getMorphType()} = '';
        $model->{$morphTo->getForeignKeyName()} = NULL;

        $model->save();
    }
}
