<?php

namespace Fjord\Crud;

use Closure;
use Fjord\Vue\Component;

class CrudForm extends Form
{
    /**
     * List of Vue components and its props.
     *
     * @var array
     */
    protected $components = [];

    /**
     * Fields for the current card are stored in here.
     *
     * @var array
     */
    protected $card = [];

    /**
     * Register new Field.
     *
     * @param mixed $name
     * @param string $id
     * @param array $params
     * @return Field $field
     */
    protected function registerField($field, string $id, $params = [])
    {
        $field = parent::registerField($field, $id, $params);

        $this->card[] = $field;

        return $field;
    }

    /**
     * Add Vue component
     *
     * @param string $name
     * @return \Fjord\Vue\Component
     */
    public function component(string $name)
    {
        $component = new Component($name);

        $this->components[] = $component;

        return $component;
    }

    /**
     * Create a new Card.
     *
     * @param any ...$params
     * @return void
     */
    public function card($closure = null, ...$params)
    {
        if ($closure instanceof Closure) {
            $closure($this);
        }

        $ids = collect($this->card)->map(function ($field) {
            return $field->id;
        });

        $card = $this->component('fj-crud-show-form')->prop('fieldIds', $ids);

        if ($this->registrar) {
            // Check if all required properties are set.
            $this->registrar->checkComplete();
        }

        $this->card = [];

        return $card;
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function getArray(): array
    {
        return [
            'components' => collect($this->components),
            'fields' => $this->registeredFields
        ];
    }
}
