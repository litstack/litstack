<?php

namespace Fjord\Support\Macros;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuilderMacros extends ServiceProvider
{
    public function boot()
    {
        $this->whereLike();
        $this->orderBy();
    }

    protected function orderBy()
    {
        Builder::macro('orderByRelation', function ($related, $column, $direction = 'asc', Closure $closure = null) {
            $table = $this->model->getTable();
            $foreignTable = $this->model->$related()->getRelated()->getTable();

            if ($this->model->$related() instanceof HasOne) {
                $foreignKey = $this->model->$related()->getForeignKeyName();
                $localKey = $this->model->$related()->getLocalKeyName();
                $this->leftJoin($foreignTable, "{$foreignTable}.{$foreignKey}", '=', "{$table}.{$localKey}")
                    ->select("{$table}.*", "{$foreignTable}.{$column} as eager_order_column");
            } else if ($this->model->$related() instanceof BelongsTo) {
                $foreignKey = $this->model->$related()->getForeignKeyName();
                $this->leftJoin($foreignTable, "{$foreignTable}.id", '=', "{$table}.{$foreignKey}")
                    ->select("{$table}.*", "{$foreignTable}.{$column} as eager_order_column");
            }

            if ($closure instanceof Closure) {
                $closure($this);
            }

            return $this->orderBy("{$foreignTable}.{$column}", $direction);
        });

        Builder::macro('orderByTranslation', function ($locale, $column, $direction = 'asc') {
            return $this->orderByRelation('translation', $column, $direction, function ($query) use ($locale) {
                $foreignTable = $this->model->translation()->getRelated()->getTable();
                $query->where("{$foreignTable}.locale", $locale);
            });
        });
    }

    /**
     * whereLike macro for query builder.
     *
     * @return void
     */
    protected function whereLike()
    {
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            return $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        Str::contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });
        });
    }
}
