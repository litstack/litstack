<?php

namespace Ignite\Search;

use Closure;
use Ignite\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class SearchAspect
{
    /**
     * Config class.
     *
     * @var string
     */
    protected $config;

    /**
     * Searchable attributes.
     *
     * @var string
     */
    protected $keys;

    /**
     * Create new SearchAspect instance.
     *
     * @param  string $config
     * @param  array  $keys
     * @return void
     */
    public function __construct($config, array $keys)
    {
        $this->config = $config;
        $this->keys = $keys;
    }

    /**
     * Get config.
     *
     * @return string
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get searchable attribute.
     *
     * @return string
     */
    public function getKeys()
    {
        if (! empty($this->keys)) {
            return $this->keys;
        }

        return Config::get($this->config)->index()->getTable()->search;
    }

    /**
     * Set icon.
     *
     * @param  string $icon
     * @return $this
     */
    public function icon($icon)
    {
        if ($icon instanceof Closure) {
            $this->iconResolver = $icon;
        } else {
            $this->iconResolver = fn ($model) => $model->getAttribute($icon);
        }

        return $this;
    }

    /**
     * Set description.
     *
     * @param  string $description
     * @return $this
     */
    public function description($description)
    {
        if ($description instanceof Closure) {
            $this->iconResolver = $description;
        } else {
            $this->iconResolver = fn ($model) => $model->getAttribute($description);
        }

        return $this;
    }

    /**
     * Set title.
     *
     * @param  string $title
     * @return $this
     */
    public function title($title)
    {
        if ($title instanceof Closure) {
            $this->titleResolver = $title;
        } else {
            $this->titleResolver = fn ($model) => $model->getAttribute($title);
        }

        return $this;
    }

    public function getTitle($model)
    {
        return call_user_func($this->titleResolver, $model);
    }

    public function getResults($query)
    {
        $config = Config::get($this->config);

        return collect($this->getKeys())->groupBy(function ($item) {
            return count(explode('.', $item));
        })->map(function ($keys, $importance) use ($config, $query) {
            return $config
                ->query()
                ->search($keys->toArray(), $query)
                ->latest()
                ->take(5)
                ->get()
                ->map(function (Model $model) use ($config, $importance) {
                    $result = (new Result($importance))->route(
                        lit()->url("{$config->route_prefix}/".$model->getKey())
                    );

                    if ($createdAt = $model->getAttribute($model->getCreatedAtColumn())) {
                        $createdAt = $createdAt->clone();
                        $createdAt->clone()->setLocale(lit()->getLocale());
                        $result->createdAt($createdAt->diffForHumans());
                    }

                    $config->searchResult($result, $model);

                    return $result;
                });
        })->flatten();
    }
}
