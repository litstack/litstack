<?php

namespace Fjord\Vue;

use Exception;
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
    protected $available = [];

    /**
     * Required Vue component props.
     *
     * @var array
     */
    protected $required = [];

    /**
     * Vue component props.
     *
     * @var array
     */
    protected $props = [];

    /**
     * Default component properties.
     *
     * @var array
     */
    protected $defaults = [];

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

        $this->setDefaults();
    }

    /**
     * Set defaults.
     *
     * @return void
     */
    protected function setDefaults()
    {
        foreach ($this->defaults as $name => $value) {
            $this->props[$name] = $value;
        }
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
     * Get missing props and attributes
     *
     * @return array
     */
    protected function getMissing()
    {
        $missing = [];
        foreach (array_merge($this->required, $this->required()) as $prop) {
            if (array_key_exists($prop, $this->props)) {
                continue;
            }

            $missing[] = $prop;
        }

        return $missing;
    }

    public function required()
    {
        // TODO: make better.
        return [];
    }

    /**
     * Check if all required props have been set.
     *
     * @return boolean
     * 
     * @throws \Exception
     */
    public function checkComplete()
    {
        if (empty($missing = $this->getMissing())) {
            return true;
        }

        throw new Exception(sprintf(
            'Missing required attributes: [%s] for component "%s"',
            implode(', ', $missing),
            $this->name
        ));
    }

    /**
     * Get array.
     *
     * @return array
     */
    public function getArray(): array
    {
        $this->checkComplete();

        return [
            'name' => $this->name,
            'props' => collect($this->props),
        ];
    }

    /**
     * Get component name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get props.
     *
     * @return array
     */
    public function getProps()
    {
        return $this->props;
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
    public function methodNotFound($method)
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
                implode(', ', $this->available)
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
        if (in_array($method, $this->available)) {
            return $this->prop($method, ...$params);
        }

        $this->methodNotFound($method);
    }
}
