<?php

namespace Fjord\Crud;

use Closure;
use Exception;
use Fjord\Support\VueProp;
use InvalidArgumentException;
use Fjord\Crud\Models\FormField;
use Fjord\Exceptions\MethodNotFoundException;

class Field extends VueProp
{
    /**
     * Is field translatable.
     *
     * @var boolean
     */
    protected $translatable = false;

    /**
     * Model class
     *
     * @var string
     */
    protected $model;

    /**
     * Field attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Authorize closure for field.
     *
     * @var Closure
     */
    protected $authorize;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [];

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'cols',
    ];

    /**
     * Available slots.
     *
     * @var array
     */
    protected $availableSlots = [
        'title',
    ];

    /**
     * Available field attributes.
     *
     * @var array
     */
    protected $available = [
        'readonly',
        'cols',
        'info',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'readonly' => false,
        'cols' => 12,
        'slots' => []
    ];

    /**
     * Create new Field instance.
     *
     * @param string $id
     * @param string $model
     * @param string|null $routePrefix
     */
    public function __construct(string $id, string $model, $routePrefix)
    {
        // Merge available, default and required properties of parent and child class.
        if (static::class != self::class) {
            $parent = new self($id, $model, $routePrefix);
            $this->available = array_merge(
                $parent->getAvailableAttributes(),
                $this->available,
            );
            $this->defaults = array_merge(
                $parent->getDefaults(),
                $this->defaults,
            );
            $this->availableSlots = array_merge(
                $parent->getAvailableSlots(),
                $this->availableSlots,
            );
            $this->required = array_merge(
                $parent->getRequiredAttributes(),
                $this->required,
            );
        } else {
            return;
        }

        $this->attributes['id'] = $id;
        $this->attributes['local_key'] = $id;
        $this->attributes['route_prefix'] = $routePrefix;
        $this->model = $model;

        if ($this->translatable) {
            $this->available[] = 'translatable';
        }

        $this->setDefaults();
        $this->attributes['component'] = $this->component;
    }

    /**
     * Cast model value for e.g. boolean.
     *
     * @param Model $value
     * @return mixed
     */
    public function cast($value)
    {
        return $value;
    }

    /**
     * Transform model value.
     *
     * @param Model $value
     * @return mixed
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * Is field component.
     *
     * @return boolean
     */
    public function isComponent()
    {
        return false;
    }

    /**
     * Is field relation.
     *
     * @return boolean
     */
    public function isRelation()
    {
        return false;
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
        $missing = [];
        foreach ($this->required as $prop) {
            if (array_key_exists($prop, $this->attributes)) {
                continue;
            }

            $missing[] = $prop;
        }

        if (empty($missing)) {
            return true;
        }

        throw new Exception(sprintf(
            'Missing required attributes: [%s] for %s field "%s"',
            implode(', ', $missing),
            lcfirst(last(explode('\\', static::class))),
            $this->attributes['id']
        ));
    }

    /**
     * Set slot component.
     *
     * @param string $slot
     * @param string $component
     * @return void
     * 
     * @throws \InvalidArgumentException
     */
    public function slot(string $slot, string $component)
    {
        if (!$this->slotExists($slot)) {
            $field = class_basename(static::class);
            throw new InvalidArgumentException("Slot {$slot} does not exist for Field {$field}. Available slots: " . implode(', ', $this->getAvailableSlots()));
        }

        $this->attributes['slots'][$slot] = $component;

        return $this;
    }

    /**
     * Check if slot exists.
     *
     * @param string $slot
     * @return void
     */
    public function slotExists(string $slot)
    {
        return in_array($slot, $this->availableSlots);
    }

    /**
     * Set authorize closure.
     *
     * @param Closure $closure
     * @return void
     */
    public function authorize(Closure $closure)
    {
        $this->authorize = $closure;
    }

    /**
     * Execute authorize method.
     *
     * @return boolean
     */
    public function authorized()
    {
        if (!$this->authorize) {
            return true;
        }

        $closure = $this->authorize;
        return $closure(fjord_user());
    }

    /**
     * Set default Field attributes.
     *
     * @return void
     */
    protected function setDefaults()
    {
        foreach ($this->defaults as $name => $value) {
            $this->attributes[$name] = $value;
        }

        $this->setTranslatable();
    }

    /**
     * Translatable.
     *
     * @return void
     */
    public function setTranslatable()
    {
        // Translatable
        $this->attributes['translatable'] = false;

        if (!$this->translatable) {
            return;
        }
        if (!is_translatable($this->model)) {
            return;
        }

        $this->attributes['translatable'] = in_array($this->id, (new $this->model)->translatedAttributes);
    }

    /**
     * Set field attribute.
     *
     * @param string $name
     * @param string|int|closure $value
     * @return self
     */
    public function setAttribute(string $name, $value = true)
    {
        if ($value instanceof Closure) {
            $value = Closure::bind($value, $this)();
        }

        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Throw a MethodNotFoundException.
     *
     * @param  array  $others
     * @param  string  $method
     * @return void
     *
     * @throws \Fjord\Exceptions\FieldMethodNotFoundException
     */
    protected function methodNotFound($method)
    {
        $allowed = $this->getAllowedMethods();

        throw new MethodNotFoundException(
            sprintf(
                'The %s method is not found for the %s field. Supported methods: %s.',
                $method,
                class_basename(static::class),
                implode(', ', $allowed)
            ),
            [
                'function' => '__call',
                'class' => self::class
            ]
        );
    }

    /**
     * Get list of allowed methods for field.
     *
     * @return array
     */
    protected function getAllowedMethods()
    {
        return array_merge($this->available, ['authorize']);
    }

    /**
     * Get avaliable attributes.
     *
     * @return array
     */
    public function getAvailableAttributes()
    {
        return $this->available;
    }

    /**
     * Get avaliable slots.
     *
     * @return array
     */
    public function getAvailableSlots()
    {
        return $this->availableSlots;
    }

    /**
     * Get required attributes.
     *
     * @return array
     */
    public function getRequiredAttributes()
    {
        return $this->required;
    }

    /**
     * Get defaults.
     *
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * Get attribute.
     *
     * @param string $name
     * @return any
     */
    public function getAttribute(string $name)
    {
        if ($name == 'authorized') {
            return $this->authorized();
        }

        return $this->attributes[$name] ?? null;
    }

    /**
     * To array.
     *
     * @return array
     */
    public function getArray(): array
    {
        foreach ($this->props as $name => $value) {
            $this->attributes[$name] = $value;
        }

        return $this->attributes;
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
        if (in_array($method, $this->available)) {
            return $this->setAttribute($method, ...$params);
        }

        $this->methodNotFound($method);
    }

    /**
     * Get attribute.
     *
     * @param string $name
     * @return any
     */
    public function __get(string $name)
    {
        return $this->getAttribute($name);
    }

    /**
     * Get attribute.
     *
     * @param string $name
     * @return any
     */
    public function __set(string $name, $value)
    {
        return $this->setAttribute($name, $value);
    }
}
