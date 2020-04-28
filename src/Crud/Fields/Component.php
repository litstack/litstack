<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;
use Illuminate\Support\Traits\ForwardsCalls;
use Fjord\Exceptions\MethodNotFoundException;

class Component extends Field
{
    use ForwardsCalls;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-component';

    /**
     * Is field translatable.
     *
     * @var boolean
     */
    protected $translatable = false;

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'comp',
    ];

    protected $available = [];

    /**
     * Create new info instance.
     *
     * @param string $id
     * @param string $model
     * @param string|null $routePrefix
     */
    public function __construct(string $id, string $model, $routePrefix)
    {
        parent::__construct($id, $model, $routePrefix);

        $this->attributes['comp'] = component($id);
    }

    /**
     * Is field component.
     *
     * @return boolean
     */
    public function isComponent()
    {
        return true;
    }

    /**
     * Get component.
     *
     * @return void
     */
    public function getComponent()
    {
        return $this->attributes['comp'];
    }

    /**
     * Call field method.
     *
     * @param string $method
     * @param array $params
     * @return static|void
     * 
     * @throws \Fjord\Exceptions\MethodNotFoundException
     */
    public function __call($method, $params = [])
    {
        try {
            $this->forwardCallTo($this->attributes['comp'], $method, $params);
        } catch (MethodNotFoundException $e) {
            try {
                return parent::__call($method, $params);
            } catch (MethodNotFoundException $e) {
                $this->attributes['comp']->methodNotFound($method);
            }
        }

        return $this;
    }
}
