<?php

namespace Fjord\Vue;

use Closure;
use Exception;
use Fjord\Support\VueProp;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Fjord\Exceptions\MethodNotFoundException;

class Component extends VueProp
{
    const PROP_TYPES = [
        'boolean',
        'integer',
        'double',
        'string',
        'array',
        'object',
    ];

    /**
     * Vue component name.
     *
     * @var string
     */
    protected $name;

    /**
     * Available Vue component props.
     *
     * @var array
     */
    protected $availableProps = [];

    /**
     * Available Vue component slots.
     *
     * @var array
     */
    protected $availableSlots = [];

    /**
     * Vue component props.
     *
     * @var array
     */
    protected $props = [];

    /**
     * Registered Vue component slots.
     *
     * @var array
     */
    protected $slots = [];

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
     * @param array $options
     * @return void
     */
    public function __construct(string $name, array $options = [])
    {
        $this->name = $name;

        $this->availableProps = $options['props'] ?? $this->availableProps;
        $this->availableSlots = $options['slots'] ?? $this->availableProps;

        $this->availableProps = array_merge($this->availableProps, $this->props());
        $this->availableSlots = array_merge($this->availableSlots, $this->slots());

        $this->setDefaults();
    }

    /**
     * Set defaults.
     *
     * @return void
     */
    protected function setDefaults()
    {
        foreach ($this->availableProps as $name => $options) {
            if (!array_key_exists('default', $options)) {
                continue;
            }

            $default = $options['default'];
            if ($default instanceof Closure) {
                $default = $default();
            }

            $this->prop($name, $default);
        }
    }


    /**
     * Register new slot.
     *
     * @param string $name
     * @param string|Component $component
     * @return void
     * 
     * @throws \InvalidArgumentException
     */
    public function slot(string $name, $component)
    {
        if (!array_key_exists($name, $this->availableSlots)) {
            $message = sprintf(
                '%s is not an available slot for Vue component %s.',
                $name,
                $this->name
            );

            if (count($this->availableSlots) > 0) {
                $message .= sprintf(
                    ' Available slots: %s',
                    implode(', ', array_keys($this->availableSlots))
                );
            }

            throw new InvalidArgumentException($message);
        }

        $this->slots[$name] = component($component);
    }

    /**
     * Available slots.
     *
     * @return array
     */
    protected function slots()
    {
        return [];
    }

    /**
     * Available props.
     *
     * @return array
     */
    protected function props()
    {
        return [];
    }

    /**
     * Bind multiple props.
     *
     * @param array $props
     * @return self
     */
    public function bind(array $props)
    {
        foreach ($props as $name => $value) {
            $this->prop($name, $value);
        }

        return $this;
    }

    /**
     * Add single prop.
     *
     * @param string $name
     * @param mixed $value
     * @return self
     */
    public function prop(string $name, $value = true)
    {
        if (array_key_exists($name, $this->availableProps)) {
            $type = $this->availableProps[$name]['type'] ?? null;
            $this->checkPropType($name, $type, $value);
        }

        $this->props[$name] = $value;

        return $this;
    }

    /**
     * Set component class.
     *
     * @param string $value
     * @return void
     */
    public function class(string $value)
    {
        if (!array_key_exists('class', $this->props)) {
            $this->props['class'] = '';
        }

        $this->props['class'] .= " {$value}";

        return $this;
    }

    /**
     * Check prop config type.
     *
     * @param string $name
     * @param string|array $type
     * @param mixed $value
     * @return void
     */
    protected function checkPropType($name, $type, $value)
    {
        if ($type === null) {
            return;
        }

        $valid = false;
        if (is_string($type)) {
            $valid = $this->isValidPropType($type, $value);
            $message = "Value must be: {$type}";
        } else {
            foreach ($type as $t) {
                $valid = $this->isValidPropType($t, $value);
                if ($valid) {
                    break;
                }
            }
            $message = "Value must be: " . implode(', ', $type);
        }

        if (!$valid) {
            throw new InvalidArgumentException(sprintf(
                'Invalid value type "%s" for prop %s in Vue component %s. %s',
                gettype($value),
                $name,
                $this->name,
                $message
            ));
        }
    }

    /**
     * Check if a prop type is valid.
     *
     * @param string $type
     * @param mixed $value
     * @return boolean
     * 
     * @throws \InvalidArgumentException
     */
    protected function isValidPropType($type, $value)
    {
        if (!in_array($type, self::PROP_TYPES)) {

            throw new InvalidArgumentException(sprintf(
                '%s is not valid prop type. Available prop type: %s',
                $type,
                implode(', ', self::PROP_TYPES)
            ));
        }

        return $type == gettype($value);
    }

    /**
     * Get missing props and attributes
     *
     * @return array
     */
    protected function getMissing($attributes, $registered)
    {
        $missing = [];

        foreach ($attributes as $name => $options) {
            if (!array_key_exists('required', $options)) {
                continue;
            }

            if (!$options['required']) {
                continue;
            }

            if (array_key_exists($name, $registered)) {
                continue;
            }

            $missing[] = $name;
        }

        return $missing;
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
        $this->checkCompleteProps();
        $this->checkCompleteSlots();
    }

    /**
     * Check for missing required props.
     *
     * @return boolean
     * 
     * @throws \Exception
     */
    public function checkCompleteProps()
    {
        if (empty($missing = $this->getMissing($this->availableProps, $this->props))) {
            return true;
        }

        throw new Exception(sprintf(
            'Missing required props: [%s] for component "%s"',
            implode(', ', $missing),
            $this->name
        ));
    }

    /**
     * Check for missing slots.
     *
     * @return boolean
     * 
     * @throws \Exception
     */
    public function checkCompleteSlots()
    {
        if (empty($missing = $this->getMissing($this->availableSlots, $this->slots))) {
            return true;
        }

        throw new Exception(sprintf(
            'Missing required slots: [%s] for component "%s"',
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
            'slots' => collect($this->slots)
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
     * Get props.
     *
     * @return array
     */
    public function getSlots()
    {
        return $this->slots;
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
            '"%s" is not a supported method for the Vue component "%s". Supported methods: %s.',
            $method,
            $this->name,
            implode(', ', $this->getSupportedMethods())
        );

        throw new MethodNotFoundException($message);
    }

    /**
     * Get supported methods.
     *
     * @return array
     */
    protected function getSupportedMethods()
    {
        return array_merge(
            array_keys($this->availableProps),
            ['class', 'prop', 'slot', 'bind']
        );
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
        if (array_key_exists($method, $this->availableProps)) {
            return $this->prop($method, ...$params);
        }

        if (in_array($method, $this->availableSlots)) {
            return $this->registerSlot(
                $method,
                $this->availableSlots[$method],
                ...$params
            );
        }

        $this->methodNotFound($method);
    }
}
