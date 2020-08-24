<?php

namespace Lit\Crud\Repositories\Relations;

use Lit\Crud\Fields\Relations\MorphOne;
use Lit\Crud\Repositories\BaseFieldRepository;
use Lit\Crud\Requests\CrudUpdateRequest;

class MorphOneRepository extends BaseFieldRepository
{
    use Concerns\ManagesRelated;

    /**
     * MorphOne field instance.
     *
     * @var MorphOne
     */
    protected $field;

    /**
     * Create new MorphManyRepository instance.
     *
     * @return void
     */
    public function __construct($config, $controller, $form, MorphOne $field)
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

        $morphOne = $this->field->getRelationQuery($model);

        $query = [
            $morphOne->getMorphType()      => get_class($model),
            $morphOne->getForeignKeyName() => $model->{$morphOne->getLocalKeyName()},
        ];

        // Remove existsing morphOne relations.
        $morphOne->where($query)->update([
            $morphOne->getMorphType()      => '',
            $morphOne->getForeignKeyName() => 0,
        ]);

        // Create new relation.
        $related->update($query);
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

        $morphOne = $this->field->getRelationQuery($model);

        $related->{$morphOne->getMorphType()} = '';
        $related->{$morphOne->getForeignKeyName()} = 0;
        $related->update();
    }
}
