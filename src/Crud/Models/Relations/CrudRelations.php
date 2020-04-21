<?php

namespace Fjord\Crud\Models\Relations;

use Fjord\Crud\Models\FormBlock;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class CrudRelations extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->media();
        $this->blocks();
        $this->manyRelation();
        $this->oneRelation();
    }

    /**
     * Media macros.
     *
     * @return void
     */
    public function media()
    {
        Builder::macro('oneMedia', function (string $fieldId) {
            return $this->getModel()
                ->morphOne(config('medialibrary.media_model'), 'model')
                ->where('collection_name', $fieldId);
        });

        Builder::macro('manyMedia', function (string $fieldId) {
            return $this->getModel()
                ->morphMany(config('medialibrary.media_model'), 'model')
                ->where('collection_name', $fieldId);
        });
    }

    /**
     * Register blocks relation macro.
     *
     * @return void
     */
    public function blocks()
    {
        Builder::macro('blocks', function ($fieldId = null) {
            $model = $this->getModel();

            $relation = $model->morphMany(FormBlock::class, 'model')
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
        Builder::macro('manyRelation', function (string $related) {
            $instance = new $related;
            $model = $this->getModel();

            return $model->belongsToMany($related, 'form_relations', 'from_model_id', 'to_model_id', $model->getKeyName(), $instance->getKeyName())
                ->where('form_relations.from_model_type', get_class($model))
                ->where('form_relations.to_model_type', $related)
                ->orderBy('form_relations.order_column');
        });
    }

    /**
     * Register oneRelation relation macro.
     *
     * @return void
     */
    public function oneRelation()
    {
        Builder::macro('oneRelation', function (string $related) {
            $instance = new $related;
            $model = $this->getModel();

            return $model->belongsToMany($related, 'form_relations', 'from_model_id', 'to_model_id', $model->getKeyName(), $instance->getKeyName())
                ->where('form_relations.from_model_type', get_class($model))
                ->where('form_relations.to_model_type', $related)
                ->orderBy('form_relations.order_column');
        });
    }
}
