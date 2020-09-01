<?php

namespace Ignite\Crud\Models\Relations;

use Ignite\Crud\Fields\ListField\ListRelation;
use Ignite\Crud\Models\ListItem;
use Ignite\Crud\Models\Relation;
use Ignite\Crud\Models\Repeatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class CrudRelations extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->list();
        $this->repeatables();
        $this->manyRelation();
        $this->oneRelation();
    }

    /**
     * Register listItems relation macro.
     *
     * @return void
     */
    public function list()
    {
        Builder::macro('listItems', function (string $fieldId) {
            $model = $this->getModel();

            $morphMany = $model->morphMany(ListItem::class, 'model');

            $relation = new ListRelation(
                $morphMany->getQuery(),
                $morphMany->getParent(),
                $morphMany->getQualifiedMorphType(),
                $morphMany->getQualifiedForeignKeyName(),
                $morphMany->getLocalKeyName()
            );

            if ($fieldId) {
                $relation->where('field_id', $fieldId);
            }

            return $relation;
        });
    }

    /**
     * Register repeatables relation macro.
     *
     * @return void
     */
    public function repeatables()
    {
        Builder::macro('repeatables', function ($fieldId = null) {
            $model = $this->getModel();

            $relation = $model->morphMany(Repeatable::class, 'model')
                ->with('translations')
                ->orderBy('order_column');

            if ($fieldId) {
                $relation->where('field_id', $fieldId);
            }

            return $relation;
        });
    }

    /**
     * Register manyRelation relation macro.
     *
     * @return void
     */
    public function manyRelation()
    {
        Builder::macro('manyRelation', function (string $related, string $fieldId) {
            $instance = new $related();
            $model = $this->getModel();

            return $model->belongsToMany($related, 'lit_relations', 'from_model_id', 'to_model_id', $model->getKeyName(), $instance->getKeyName())
                ->where('lit_relations.from_model_type', get_class($model))
                ->where('lit_relations.to_model_type', $related)
                ->where('field_id', $fieldId)
                ->orderBy('lit_relations.order_column');
        });
    }

    /**
     * Register oneRelation relation macro.
     *
     * @return void
     */
    public function oneRelation()
    {
        Builder::macro('oneRelation', function (string $related, string $fieldId) {
            $instance = new $related();
            $model = $this->getModel();

            return $model->hasOneThrough($related, Relation::class, 'from_model_id', $model->getKeyName(), $instance->getKeyName(), 'to_model_id')
                ->where('lit_relations.from_model_type', get_class($model))
                ->where('lit_relations.to_model_type', $related)
                ->where('field_id', $fieldId)
                ->orderBy('lit_relations.order_column');
        });
    }
}
