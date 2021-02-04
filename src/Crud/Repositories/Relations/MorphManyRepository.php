<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudBaseController;
use Ignite\Crud\Fields\Relations\MorphMany;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;

class MorphManyRepository extends RelationRepository
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
     *
     * @param  ConfigHandler      $config
     * @param  CrudBaseController $controller
     * @param  BaseForm           $form
     * @param  MorphMany          $field
     * @return void
     */
    public function __construct($config, $controller, $form, MorphMany $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new morphMany relation.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @return void
     */
    public function create(CrudUpdateRequest $request, $model)
    {
        $this->checkMaxItems($model);

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
     * @param  mixed             $model
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        if (! $related = $this->getRelatedOrDelete($request, $model)) {
            return;
        }

        $morphMany = $this->field->getRelationQuery($model);

        $related->where([
            $morphMany->getMorphType()      => get_class($model),
            $morphMany->getForeignKeyName() => $model->{$morphMany->getLocalKeyName()},
        ])->update([
            $morphMany->getMorphType()      => '',
            $morphMany->getForeignKeyName() => null,
        ]);
    }
}
