<?php

namespace Fjord\Crud\Fields;

use Closure;
use Fjord\Crud\BaseField;
use Fjord\Crud\Casts\Route as RouteCast;
use Fjord\Crud\Fields\Route\RouteCollection;
use Fjord\Crud\Fields\Traits\FieldHasRules;

class Route extends BaseField
{
    use FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-route';

    /**
     * Required attributes.
     *
     * @var array
     */
    public $required = ['collection'];

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

    protected function renderCollection(RouteCollection $collection)
    {
        return $collection->map(function ($item) {
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
        })->toArray();
    }

    public function cast($value)
    {
        return (new RouteCast)->get(null, '', $value, []);
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
        app('fjord.crud.route.resolver')->register($name, $closure);
    }
}
