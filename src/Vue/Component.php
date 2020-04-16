<?php

namespace Fjord\Vue;

use BadMethodCallException;
use Illuminate\Support\Str;
use Fjord\Application\Config\ConfigItem;

class Component extends ConfigItem
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
    public function toArray()
    {
        return [
            'name' => $this->name,
            'props' => $this->props,
        ];
    }

    /**
     * Call component method.
     *
     * @param string $method
     * @param array $params
     * @return void
     * 
     * @throws BadMethodCallException
     */
    public function __call($method, $params = [])
    {
        if ($this->class !== null) {
            if ($this->class->hasProp($method)) {
                return $this->prop($method, ...$params);
            }
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }
}
