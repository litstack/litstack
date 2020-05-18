<?php

namespace Fjord\Support\Macros;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    protected $macros = [
        BuilderSearch::class,
        FormMarkdown::class
    ];

    public function boot()
    {
        $this->registerMacros();
        $this->orderBy();
    }

    public function registerMacros()
    {
        foreach ($this->macros as $macro) {
            new $macro;
        }
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
}
