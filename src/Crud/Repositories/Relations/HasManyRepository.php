<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudBaseController;
use Ignite\Crud\Fields\Relations\HasMany;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;

class HasManyRepository extends RelationRepository
{
    use Concerns\ManagesRelated;

    /**
     * HasMany field instance.
     *
     * @var HasMany
     */
    protected $field;

    /**
     * Create new HasManyRepository instance.
     *
     * @param  ConfigHandler      $config
     * @param  CrudBaseController $controller
     * @param  BaseForm           $form
     * @param  HasMany            $field
     * @return void
     */
    public function __construct(ConfigHandler $config, CrudBaseController $controller, BaseForm $form, HasMany $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Create new hasMany relation.
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
        $hasMany = $this->field->getRelationQuery($model);

        $related->{$hasMany->getForeignKeyName()} = $model->{$hasMany->getLocalKeyName()};

        // Sortable
        if ($this->field->sortable) {
            $related->{$this->field->orderColumn} = $hasMany->count();
        }

        $related->update();
    }

    /**
     * Remove hasMany relation.
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

        $hasMany = $this->field->getRelationQuery($model);

        $related->update([
            $hasMany->getForeignKeyName() => null,
        ]);
    }
}
