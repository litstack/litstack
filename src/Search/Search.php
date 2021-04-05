<?php

namespace Ignite\Search;

use Ignite\Support\Facades\Config;
use Illuminate\Support\Arr;

class Search
{
    protected $aspects = [];

    /**
     * Add search aspect.
     *
     * @param  string $config
     * @param  array  ...$keys
     * @return $this
     */
    public function add($config, ...$keys)
    {
        $this->aspects[] = $aspect = new SearchAspect($config, Arr::flatten($keys));

        return $this;
    }

    public function search($query)
    {
        if (! $query) {
            return collect([]);
        }

        return collect($this->aspects)
            ->filter(function (SearchAspect $aspect) {
                return Config::get($aspect->getConfig())->can('read');
            })
            ->map(function (SearchAspect $aspect) use ($query) {
                return $aspect->getResults($query);
            })
            ->flatten()
            ->sortBy(function (Result $result) {
                return $result->getImportance();
            })
            ->take(5)
            ->values();
    }
}
