<?php

namespace Fjord\Vue;

use Fjord\Support\VueProp;
use Illuminate\Support\Str;
use Fjord\Exceptions\MethodNotFoundException;

class Component extends VueProp
{
    /**
     * Vue component name.
     *
     * @var string
     */
    protected $name;

    /**
     * Vue component props.
     *
     * @var array
     */
    protected $props = [];

    /**
     * Instance of component class.
     *
     * @var instance
     */
    protected $class;

    /**
     * Create new Component instance.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        $this->loadClass();
    }

    protected function loadClass()
    {
        $className = 'Fjord\Vue\Components\\' . ucfirst(Str::camel($this->name));

        if (!class_exists($className)) {
            return;
        }

        $this->class = new $className;
    }

    /**
     * Add multiple props.
     *
     * @param array $props
     * @return self
     */
    public function props(array $props)
    {
        $this->props = array_merge($this->props, $props);

        return $this;
    }

    /**
     * Add single prop.
     *
     * @param string $name
     * @param any $value
     * @return self
     */
    public function prop(string $name, $value)
    {
        $this->props[$name] = $value;

        return $this;
    }

    /**
     * Get array.
     *
     * @return array
     */
    public function getArray(): array
    {
        return [
            'name' => $this->name,
            'props' => $this->props,
        ];
    }

    /**
     * Throw a MethodNotFoundException.
     *
     * @param  array  $others
     * @param  string  $method
     * @return void
     *
     * @throws \Fjord\Exceptions\MethodNotFoundException
     */
    protected function methodNotFound($method)
    {
        $message = sprintf(
            '"%s" is not a supported prop for the Vue component "%s". Supported methods: %s.',
            $method,
            $this->name,
            implode(', ', $this->getSupportedMethods())
        );
        if ($this->class) {
            $message .= sprintf(
                ' Supported props: %s',
                implode(', ', $this->class->getProps())
            );
        }

        throw new MethodNotFoundException($message);
    }

    /**
     * Get supported methods.
     *
     * @return array
     */
    protected function getSupportedMethods()
    {
        return ['props', 'prop'];
    }

    /**
     * Call component method.
     *
     * @param string $method
     * @param array $params
     * @return void
     * 
     * @throws \Fjord\Exceptions\MethodNotFoundException
     */
    public function __call($method, $params = [])
    {
        if ($this->class !== null) {
            if ($this->class->hasProp($method)) {
                return $this->prop($method, ...$params);
            }
        }

        $this->methodNotFound($method);
    }
}
