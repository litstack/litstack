<?php

namespace Ignite\Page\Table;

use Ignite\Contracts\Page\Action;
use Ignite\Contracts\Page\Table as TableContract;
use Ignite\Page\Actions\DropdownItemAction;
use Ignite\Support\HasAttributes;
use Ignite\Support\VueProp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class BaseTable extends VueProp implements TableContract
{
    use HasAttributes;

    /**
     * Table column builder.
     *
     * @var ColumnBuilder
     */
    protected $builder;

    /**
     * Table actions.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Table route prefix.
     *
     * @var string
     */
    protected $routePrefix;

    /**
     * Action factory.
     *
     * @var string
     */
    protected $actionFactory;

    /**
     * Create new Table instance.
     *
     * @param ColumnBuilder $builder
     */
    public function __construct(ColumnBuilder $builder)
    {
        $this->builder = $builder;
        $this->builder->setParent($this);
        $this->actionFactory = new DropdownItemAction;

        $this->setDefaults();
    }

    /**
     * Set actions.
     *
     * @param  array $actions
     * @return $this
     */
    public function setActions($actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Add action.
     *
     * @param  string $title
     * @param  string $action
     * @return $this
     */
    public function action($title, $action)
    {
        $this->actions[] = $this->actionFactory->make($title, $action);

        return $this;
    }

    /**
     * Get actions.
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Set defaults.
     *
     * @return void
     */
    public function setDefaults()
    {
        $this->setAttribute('controls', collect([]));
        $this->sortByDefault('id.desc');
        $this->perPage(10);
        $this->search(['title']);
        $this->sortBy([
            'id.desc' => __lit('crud.sort_new_to_old'),
            'id.asc'  => __lit('crud.sort_old_to_new'),
        ]);
    }

    /**
     * Set table route prefix.
     *
     * @param  string $routePrefix
     * @return $this
     */
    public function routePrefix(string $routePrefix)
    {
        $this->setAttribute('route_prefix', $routePrefix);

        return $this;
    }

    /**
     * Set singular name.
     *
     * @param  string $name
     * @return $this
     */
    public function singularName($name)
    {
        $this->setAttribute('singularName', $name);

        return $this;
    }

    /**
     * Set plural name.
     *
     * @param  string $name
     * @return void
     */
    public function pluralName($name)
    {
        $this->setAttribute('pluralName', $name);

        return $this;
    }

    /**
     * Table card width.
     *
     * @param  int|float $width
     * @return $this
     */
    public function width($width)
    {
        $this->component->prop('width', $width);

        return $this;
    }

    /**
     * Per page.
     *
     * @param  int   $perPage
     * @return $this
     */
    public function perPage(int $perPage)
    {
        $this->setAttribute('perPage', $perPage);

        return $this;
    }

    /**
     * Set sort by default.
     *
     * @param  string $key
     * @return $this
     */
    public function sortByDefault(string $attribute)
    {
        $this->setAttribute('sortByDefault', $attribute);

        return $this;
    }

    /**
     * Set search keys.
     *
     * @param  array $keys
     * @return $this
     */
    public function search(...$keys)
    {
        $keys = Arr::wrap($keys);
        if (count($keys) == 1) {
            if (is_array($keys[0])) {
                $keys = $keys[0];
            }
        }

        $this->setAttribute('search', $keys);

        return $this;
    }

    /**
     * Set sortBy keys.
     *
     * @param  array $sortBy
     * @return void
     */
    public function sortBy(...$keys)
    {
        $keys = Arr::wrap($keys);
        if (count($keys) == 1) {
            if (is_array($keys[0])) {
                $keys = $keys[0];
            }
        }
        $this->setAttribute('sortBy', $keys);

        return $this;
    }

    /**
     * Set table filters.
     *
     * @param  array $filter
     * @return $this
     */
    public function filter(array $filter)
    {
        $this->setAttribute('filter', $filter);

        return $this;
    }

    /**
     * Set default filter.
     *
     * @param  string ...$filter
     * @return $this
     */
    public function defaultFilter(...$filter)
    {
        $this->setAttribute('defaultFilter', $filter);

        return $this;
    }

    /**
     * Get builder.
     *
     * @return ColumnBuilder|mixed
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Render CrudIndexTable for Vue.
     *
     * @return array
     */
    public function render(): array
    {
        return array_merge($this->attributes, [
            'cols'    => $this->builder,
            'actions' => $this->actions,
        ]);
    }

    /**
     * Get attribute by name.
     *
     * @param  string $name
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function __get($name)
    {
        if ($this->hasAttribute($name)) {
            return $this->getAttribute($name);
        }

        throw new InvalidArgumentException("Property [{$name}] does not exist on ".static::class);
    }

    /**
     * Set attribute value.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return void
     */
    public function __set($attribute, $value)
    {
        if ($this->hasAttribute($attribute)) {
            $this->attribute[$attribute] = $value;

            return;
        }

        $this->{$attribute} = $value;
    }
}
