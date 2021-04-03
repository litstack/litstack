<?php

namespace Ignite\Support\Macros;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BuilderSearch
{
    /**
     * Create new BuilderSearch instance.
     *
     * @return void
     */
    public function __construct()
    {
        $self = $this;

        Builder::macro('search', function ($attributes, string $searchTerm) use ($self) {
            return $self->search($this, $attributes, $searchTerm);
        });
    }

    /**
     * search macro for query builder.
     *
     * @return Builder
     */
    public function search(Builder $query, $attributes, string $searchTerm)
    {
        return $query->where(function (Builder $query) use ($attributes, $searchTerm) {
            $or = false;
            foreach (Arr::wrap($attributes) as $attribute) {
                $this->searchRelationOrAttribute($query, $attribute, $searchTerm, $or);
                $or = true;
            }
        });
    }

    /**
     * Search relation or attribute.
     *
     * @param Builder $query
     * @param string  $attribute
     * @param string  $searchTerm
     * @param bool    $or
     *
     * @return void
     */
    protected function searchRelationOrAttribute($query, $attribute, $searchTerm, $or)
    {
        $query->when(
            Str::contains($attribute, '.'),
            function (Builder $query) use ($attribute, $searchTerm, $or) {
                $partials = explode('.', $attribute);
                $relationName = array_shift($partials);
                $relationAttribute = implode('.', $partials);
                $this->whereRelatedAttributeLike(
                    $query,
                    $relationName,
                    $relationAttribute,
                    $searchTerm,
                    $or
                );
            },
            function (Builder $query) use ($attribute, $searchTerm, $or) {
                $this->whereAttributeLike($query, $attribute, $searchTerm, $or);
            }
        );
    }

    /**
     * Where related attribute like.
     *
     * @param Builder $query
     * @param string  $relationName
     * @param string  $attribute
     * @param mixed   $searchTerm
     *
     * @return void
     */
    public function whereRelatedAttributeLike($query, $relationName, $attribute, $searchTerm, $or = false)
    {
        $method = $or ? 'orWhereHas' : 'whereHas';

        return $query->{$method}($relationName, function (Builder $query) use ($attribute, $searchTerm) {
            $this->searchRelationOrAttribute($query, $attribute, $searchTerm, false);
        });
    }

    /**
     * Where attribute like.
     *
     * @param Builder $query
     * @param string  $attribute
     * @param mixed   $searchTerm
     * @param bool    $or
     *
     * @return void
     */
    public function whereAttributeLike($query, $attribute, $searchTerm, $or = true)
    {
        if (! is_attribute_translatable($attribute, $query->getModel())) {
            $column = $query->getModel()->getTable().'.'.$attribute;

            $method = $or ? 'orWhere' : 'where';
            $query->{$method}($column, 'LIKE', "%{$searchTerm}%");
        } else {
            $method = $or ? 'orWhereTranslationLike' : 'whereTranslationLike';
            $query->{$method}($attribute, "%{$searchTerm}%");
        }
    }
}
