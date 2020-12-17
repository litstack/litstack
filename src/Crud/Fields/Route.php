<?php

namespace Ignite\Crud\Fields;

use Closure;
use Ignite\Crud\BaseField;
use Ignite\Crud\Casts\Route as RouteCast;
use Ignite\Crud\Fields\Route\RouteCollection;
use Ignite\Crud\Fields\Traits\FieldHasRules;

class Route extends BaseField
{
    use FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-route';

    /**
     * Required attributes.
     *
     * @var array
     */
    public $required = ['collection'];

    /**
     * Determines if the route can be empty.
     *
     * @var bool
     */
    protected $canBeEmpty = false;

    /**
     * Set route collection.
     *
     * @return void
     */
    public function collection(string $name)
    {
        $this->setAttribute('collection', $name);

        return $this;
    }

    /**
     * Add's empty option to unset route.
     *
     * @param  bool $empty
     * @return $this
     */
    public function allowEmpty(bool $empty = true)
    {
        $this->canBeEmpty = $empty;

        return $this;
    }

    /**
     * Render field.
     *
     * @return array
     */
    public function render(): array
    {
        $this->setOptions();

        return parent::render();
    }

    /**
     * Set options for route select field.
     *
     * @return void
     */
    protected function setOptions()
    {
        $collection = RouteCollection::resolve($this->getAttribute('collection'));

        $this->setAttribute('options', $this->renderCollection($collection));
    }

    /**
     * Render route collection.
     *
     * @param  RouteCollection $collection
     * @return array
     */
    protected function renderCollection(RouteCollection $collection)
    {
        $routes = $collection->map(function ($item) {
            if ($item instanceof RouteCollection) {
                return [
                    'label'   => $item->getTitle(),
                    'options' => $this->renderCollection($item),
                ];
            }

            return [
                'text'  => $item->getTitle(),
                'value' => $item->getId(),
            ];
        });

        if ($this->canBeEmpty) {
            $routes->prepend('---', '');
        }

        return $routes->toArray();
    }

    /**
     * Cast value.
     *
     * @param  string|null    $value
     * @return RouteItem|null
     */
    public function castValue($value)
    {
        return app(RouteCast::class)->get(null, '', $value, []);
    }

    /**
     * Register route collection.
     *
     * @param  string  $name
     * @param  Closure $closure
     * @return void
     */
    public static function register(string $name, Closure $closure)
    {
        app('lit.crud.route.resolver')->register($name, $closure);
    }
}
