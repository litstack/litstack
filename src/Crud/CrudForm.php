<?php

namespace Fjord\Crud;

use Closure;
use Exception;
use Fjord\Vue\Component;

class CrudForm extends BaseForm
{
    /**
     * List of Vue components and its props.
     *
     * @var array
     */
    protected $components = [];

    /**
     * Current card.
     *
     * @var Component
     */
    protected $card;

    /**
     * Is registering component in card.
     *
     * @var boolean
     */
    protected $inCard = false;

    /**
     * Is registering component in card.
     *
     * @return boolean
     */
    public function inCard()
    {
        return $this->inCard;
    }

    /**
     * Register new Field.
     *
     * @param mixed $name
     * @param string $id
     * @param array $params
     * @return Field $field
     * 
     * @throws \Exception
     */
    protected function registerField($field, string $id, $params = [])
    {
        if (!$this->inCard()) {
            throw new Exception("Fields must be registered in cards. Call \$form->card(function... ); and register fields inside closure.");
        }

        $field = parent::registerField($field, $id, $params);

        if ($this->inCard()) {
            $this->card
                ->component('fj-crud-show-form')
                ->prop('field', $field);
        }

        return $field;
    }

    /**
     * Add Vue component
     *
     * @param string $component
     * @return \Fjord\Vue\Component
     */
    public function component(string $component)
    {
        if ($this->inCard()) {
            return parent::component($component);
        }

        $component = component($component);

        $this->components[] = $component;

        return $component;
    }

    /**
     * Create a new Card.
     *
     * @param any ...$params
     * @return void
     */
    public function info(string $title)
    {
        $info = $this->component('fj-info')->title($title);

        if ($this->inCard()) {
            $info->heading('h6');
        } else {
            $info->class('pt-3');
        }

        return $info;
    }

    /**
     * Line component.
     *
     * @return void
     */
    public function line()
    {
        return $this->component('fj-line');
    }

    /**
     * Get current card.
     *
     * @return array $card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Create a new Card.
     *
     * @param Closure $closure
     * @param any ...$params
     * @return void
     */
    public function card(Closure $closure = null, ...$params)
    {
        $card = $this->component('fj-card');

        $this->card = $card;

        $this->inCard = true;
        $closure($this);
        $this->inCard = false;

        if ($this->registrar) {
            // Check if all required properties are set.
            $this->registrar->checkComplete();
        }

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
