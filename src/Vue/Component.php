<?php

namespace Fjord\Vue;

use Closure;
use Exception;
use Fjord\Exceptions\MethodNotFoundException;
use Fjord\Support\VueProp;
use Fjord\Vue\Contracts\AuthorizableContract;
use Fjord\Vue\Traits\Authorizable;
use InvalidArgumentException;

class Component extends VueProp implements AuthorizableContract
{
    use Authorizable;

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
     * @param array  $options
     *
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
        // Props.
        foreach ($this->availableProps as $name => $options) {
            if (! array_key_exists('default', $options)) {
                continue;
            }

            $default = $options['default'];
            if ($default instanceof Closure) {
                $default = $default();
            }

            $this->prop($name, $default);
        }
        // Slots.
        foreach ($this->availableSlots as $name => $options) {
            if (! $this->hasSlotMany($name)) {
                continue;
            }
            $this->slots[$name] = collect([]);
        }
    }

    /**
     * Register new slot.
     *
     * @param string           $name
     * @param string|Component $component
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function slot(string $name, $component)
    {
        if (! array_key_exists($name, $this->availableSlots)) {
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

        if ($this->hasSlotMany($name)) {
            $this->slots[$name][] = component($component);
        } else {
            $this->slots[$name] = component($component);
        }

        return $this;
    }

    /**
     * Has slot many components.
     *
     * @param string $slot
     *
     * @return bool
     */
    public function hasSlotMany(string $slot)
    {
        if (! array_key_exists($slot, $this->availableSlots)) {
            return;
        }

        $options = $this->availableSlots[$slot];
        if (! array_key_exists('many', $options)) {
            return false;
        }

        return $options['many'];
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
     *
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
     * @param mixed  $value
     *
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
     *
     * @return void
     */
    public function class(string $value)
    {
        if (! array_key_exists('class', $this->props)) {
            $this->props['class'] = '';
        }

        $this->props['class'] .= " {$value}";

        return $this;
    }

    /**
     * Check prop config type.
     *
     * @param string       $name
     * @param string|array $type
     * @param mixed        $value
     *
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
            if (class_exists($type)) {
                $message = "Value must be instance of {$type}";
            } else {
                $message = "Value must be: {$type}";
            }
        } else {
            foreach ($type as $t) {
                $valid = $this->isValidPropType($t, $value);
                if ($valid) {
                    break;
                }
            }
            $message = 'Value must be: '.implode(', ', $type);
        }

        if (! $valid) {
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
     * @param mixed  $value
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    protected function isValidPropType($type, $value)
    {
        // Allow self::PROP_TYPES and classes.
        if (! in_array($type, self::PROP_TYPES) && ! class_exists($type)) {
            throw new InvalidArgumentException(sprintf(
                '%s is not valid prop type. Available prop type: %s',
                $type,
                implode(', ', self::PROP_TYPES)
            ));
        }

        if (class_exists($type)) {
            return $value instanceof $type;
        }

        return $type == gettype($value);
    }

    /**
     * Get missing props and attributes.
     *
     * @return array
     */
    protected function getMissing($attributes, $registered)
    {
        $missing = [];

        foreach ($attributes as $name => $options) {
            if (! array_key_exists('required', $options)) {
                continue;
            }

            if (! $options['required']) {
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
     * @throws \Exception
     *
     * @return bool
     */
    public function checkComplete()
    {
        $this->checkCompleteProps();
        $this->checkCompleteSlots();
    }

    /**
     * Check for missing required props.
     *
     * @throws \Exception
     *
     * @return bool
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
     * @throws \Exception
     *
     * @return bool
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
     * Execute extensions for component.
     *
     * @return void
     */
    public function extend()
    {
        if (! fjord_app()->get('vue')->hasBeenBuilt()) {
            return;
        }

        fjord_app()->get('vue')->extend($this);
    }

    /**
     * Get array.
     *
     * @return array
     */
    public function render(): array
    {
        $this->extend();

        $this->checkComplete();

        return [
            'name'  => $this->name,
            'props' => collect($this->props),
            'slots' => collect($this->slots),
        ];
    }

    /**
     * Get component name.
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
     * Get available props.
     *
     * @return array
     */
    public function getAvailableProps()
    {
        return $this->availableProps;
    }

    /**
     * Get available slots.
     *
     * @return array
     */
    public function getAvailableSlots()
    {
        return $this->availableSlots;
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
     * @param array  $others
     * @param string $method
     *
     * @throws \Fjord\Exceptions\MethodNotFoundException
     *
     * @return void
     */
    public function methodNotFound($method, $options = [])
    {
        if (empty($options)) {
            $options = [
                'function' => '__call',
                'class'    => self::class,
            ];
        }

        $message = sprintf(
            '"%s" is not a supported method for the Vue component "%s". Supported methods: %s.',
            $method,
            $this->name,
            implode(', ', $this->getSupportedMethods())
        );

        throw new MethodNotFoundException($message, $options);
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

    public function __get(string $name)
    {
        if (array_key_exists($name, $this->props)) {
            return $this->props[$name];
        }

        return $this->{$name};
    }

    /**
     * Call component method.
     *
     * @param string $method
     * @param array  $params
     *
     * @throws \Fjord\Exceptions\MethodNotFoundException
     *
     * @return void
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

        if (self::class == static::class) {
            return $this->prop($method, ...$params);
        }

        $this->methodNotFound($method);
    }
}
