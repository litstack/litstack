<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;
use Fjord\Exceptions\MethodNotFoundException;
use Illuminate\Support\Traits\ForwardsCalls;

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
     * Set default attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->setComponent($this->id);
    }

    /**
     * Set component.
     *
     * @param string $name
     *
     * @return void
     */
    public function setComponent(string $name)
    {
        $this->id = $name;
        $this->setAttribute('comp', component($name));
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
     * @param array  $params
     *
     * @throws \Fjord\Exceptions\MethodNotFoundException
     *
     * @return static|void
     */
    public function __call($method, $params = [])
    {
        try {
            return $this->forwardCallTo($this->attributes['comp'], $method, $params);
        } catch (MethodNotFoundException $e) {
            try {
                $result = parent::__call($method, $params);
            } catch (MethodNotFoundException $e) {
                $this->attributes['comp']->methodNotFound($method, [
                    'function' => '__call',
                    'class'    => self::class,
                ]);
            } finally {
                $this->prop($method, ...$params);

                return $result;
            }
        }

        return $this;
    }
}
