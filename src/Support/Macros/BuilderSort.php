<?php

namespace Ignite\Support\Macros;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use InvalidArgumentException;

class BuilderSort
{
    /**
     * Query builder instance.
     *
     * @var Builder
     */
    protected $query;

    /**
     * Create new BuilderSearch instance.
     *
     * @return void
     */
    public function __construct()
    {
        $self = $this;

        Builder::macro('sort', function (string $column, string $direction = 'asc') use ($self) {
            $self->setQuery($this);

            return $self->sort($column, $direction);
        });
    }

    /**
     * Set query builder instance.
     *
     * @param Builder $query
     *
     * @return void
     */
    public function setQuery(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * Apply sort to query builder.
     *
     * @param string $column
     * @param string $direction
     *
     * @return Builder
     */
    public function sort(string $column, string $direction): Builder
    {
        if ($this->isRelatedColumn($column)) {
            [$related, $column] = explode('.', $column);

            return $this->sortByRelation($related, $column, $direction);
        }

        return $this->sortColumn($column, $direction);
    }

    /**
     * Contains column string related column.
     *
     * @param string $column
     *
     * @return bool
     */
    protected function isRelatedColumn(string $column)
    {
        return Str::contains($column, '.');
    }

    /**
     * Sort by related column.
     *
     * @param  string  $related
     * @param  string  $column
     * @param  string  $direction
     * @param  Closure $closure
     * @return Builder
     */
    protected function sortByRelation(
        string $related,
        string $column,
        string $direction,
        Closure $closure = null
    ): Builder {
        $table = $this->getModel()->getTable();
        $foreignTable = $this->getModel()->$related()->getRelated()->getTable();
        $relation = $this->getModel()->$related();

        if ($relation instanceof HasOne) {
            $foreignKey = $relation->getForeignKeyName();
            $localKey = $relation->getLocalKeyName();
        } elseif ($relation instanceof BelongsTo) {
            $foreignKey = $relation->getOwnerKeyName();
            $localKey = $relation->getForeignKeyName();
        } else {
            throw new InvalidArgumentException(sprintf(
                'Builder macro sort doesnt work for %s relation. Available relations: %s',
                basename(get_class($relation)),
                implode(', ', ['HasOne', 'BelongsTo'])
            ));
        }

        $this->query
            ->leftJoin($foreignTable, "{$foreignTable}.{$foreignKey}", '=', "{$table}.{$localKey}")
            ->select("{$table}.*", "{$foreignTable}.{$column} as eager_order_column");

        // Allow to modify query in closure.
        if ($closure instanceof Closure) {
            $closure($this->query, $foreignTable);
        }

        $this->query->orderBy("{$foreignTable}.{$column}", $direction);

        return $this->query;
    }

    /**
     * Sort column.
     *
     * @param  string  $column
     * @param  string  $direction
     * @return Builder
     */
    protected function sortColumn(string $column, string $direction): Builder
    {
        if (! is_attribute_translatable($column, $this->getModel())) {
            return $this->query->orderBy($column, $direction);
        } else {
            return $this->sortByTranslation($column, $direction);
        }
    }

    /**
     * Order by translated column.
     *
     * @param  string  $column
     * @param  string  $direction
     * @return Builder
     */
    protected function sortByTranslation(string $column, string $direction): Builder
    {
        return $this->sortByRelation(
            'translation',
            $column,
            $direction,
            function (Builder $query, $table) {
                $query->where("{$table}.locale", app()->getLocale());
            }
        );
    }

    /**
     * Get Model instance from builder.
     *
     * @return mixed
     */
    protected function getModel()
    {
        return $this->query->getModel();
    }
}
