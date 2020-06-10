<?php

namespace Fjord\Crud;

use Closure;
use Fjord\Exceptions\InvalidArgumentException;

class CrudShow extends BaseForm
{
    /**
     * List of Vue components and its props.
     *
     * @var array
     */
    protected $components = [];

    /**
     * Is registering component in card.
     *
     * @var boolean
     */
    protected $inCard = false;

    /**
     * Register new Field.
     *
     * @param mixed $field
     * @param string $id
     * @param array $params
     * @return Field $field
     */
    public function registerField($field, string $id, $params = [])
    {
        if (!$this->inWrapper()) {
            throw new InvalidArgumentException('Fields must be registered inside a wrapper.', [
                'function' => '__call'
            ]);
        }

        return parent::registerField($field, $id, $params);
    }

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
     * Add Vue component
     *
     * @param string $component
     * @return \Fjord\Vue\Component
     */
    public function component($component)
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
    public function info(string $title = '')
    {
        $info = $this->component('fj-info')->title($title);

        if ($this->inCard()) {
            $info->heading('h6');
        }

        return $info;
    }

    /**
     * Create b-card wrapper.
     *
     * @param int $cols
     * @param Closure $closure
     * @return void
     */
    public function card(Closure $closure)
    {
        return $this->wrapper('fj-field-wrapper-card', function ($form) use ($closure) {
            $this->inCard = true;
            $closure($form);
            $this->inCard = false;
        });
    }

    /**
     * Get CrudForm components.
     *
     * @return array
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function render(): array
    {
        return array_merge(parent::render(), [
            'components' => collect($this->components)
        ]);
    }
}
