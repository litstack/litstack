<?php

namespace Fjord\Crud\Repositories\Relations;

use Fjord\Crud\Fields\Relations\HasOne;
use Fjord\Crud\Fields\Relations\MorphMany;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Repositories\BaseFieldRepository;

class MorphManyRepository extends BaseFieldRepository
{
    use Concerns\ManagesRelated;

    /**
     * MorphMany field instance.
     *
     * @var MorphMany
     */
    protected $field;

    /**
     * Create new MorphManyRepository instance.
     */
    public function __construct($config, $controller, $form, MorphMany $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new morphMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param mixed $model
     * @return void
     */
    public function create(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $morphMany = $this->field->getRelationQuery($model);

        $related->{$morphMany->getMorphType()} = get_class($model);
        $related->{$morphMany->getForeignKeyName()} = $model->{$morphMany->getLocalKeyName()};

        // Sortable
        if ($this->field->sortable) {
            $related->{$this->field->orderColumn} = $morphMany->count();
        }

        $related->update();
    }

    /**
     * Remove morphMany relation.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed $model
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        $related = $this->getRelated($request, $model);

        $morphMany = $this->field->getRelationQuery($model);

        $related->where([
            $morphMany->getMorphType() => get_class($model),
            $morphMany->getForeignKeyName() => $model->{$morphMany->getLocalKeyName()}
        ])->update([
            $morphMany->getMorphType() => '',
            $morphMany->getForeignKeyName() => null
        ]);
    }
}
