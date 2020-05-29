<?php

namespace Fjord\Crud;

use Closure;
use Exception;
use Fjord\Support\VueProp;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Fjord\Support\Facades\Form;
use Illuminate\Support\Traits\ForwardsCalls;
use Fjord\Exceptions\MethodNotFoundException;
use Fjord\Crud\Exceptions\MissingAttributeException;

class Field extends VueProp
{
    use ForwardsCalls;

    /**
     * Model class
     *
     * @var string
     */
    protected $model;

    /**
     * Form instance.
     *
     * @var string
     */
    protected $form;

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
     * Available slots.
     *
     * @var array
     */
    protected $availableSlots = [
        'title',
    ];

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'width',
    ];

    /**
     * Required attributes.
     *
     * @var array
     */
    public $requiredAttributes = [];

    /**
     * Available field attributes.
     *
     * @var array
     */
    protected $available = [
        'readonly',
        'width',
        'info',
        'class',
        'dependsOn'
    ];

    /**
     * Available field attributes.
     *
     * @var array
     */
    public $availableAttributes = [];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'readonly' => false,
        'width' => 12,
        'slots' => [],
        'class' => ''
    ];

    /**
     * Default field attributes.
     *
     * @var array
     */
    public $defaultAttributes = [];

    /**
     * Properties that should be merged from traits or extensions.
     *
     * @var array
     */
    protected $mergeProperties = [
        'defaultAttributes',
        'availableAttributes',
        'requiredAttributes'
    ];

    /**
     * Saveable field.
     *
     * @var boolean
     */
    protected $save = true;

    /**
     * Fill to attribute.
     *
     * @var boolean
     */
    public $fill = true;

    /**
     * Extensions
     *
     * @var array
     */
    public $extensions = [];

    /**
     * Create new Field instance.
     *
     * @param string $id
     * @param string $model
     * @param string|null $routePrefix
     * @param mixed $form
     */
    public function __construct(string $id, string $model, $routePrefix, $form)
    {
        $this->attributes['id'] = $id;
        $this->attributes['local_key'] = $id;
        $this->attributes['route_prefix'] = $routePrefix;
        $this->model = $model;
        $this->form = $form;

        foreach (Form::getFieldExtensions($this) as $extensionClass) {
            $this->extensions[] = new $extensionClass($this);
        }
        $this->mergeExtensionProperties();
        $this->mergeTraitProperties();

        $this->setDefaultAttributes();
        $this->attributes['component'] = $this->component;
    }

    /**
     * Get extensions.
     *
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Set dependency.
     *
     * @param string $key
     * @param string|int $value
     * @return void
     */
    public function dependsOn(string $key, $value)
    {
        $this->setAttribute('dependsOn', [
            'key' => $key,
            'value' => $value
        ]);

        return $this;
    }

    /**
     * Is field saveable.
     *
     * @return boolean
     */
    public function canSave()
    {
        return $this->save;
    }

    /**
     * Should field be registered in form.
     *
     * @return boolean
     */
    public function register()
    {
        return true;
    }

    /**
     * Format value before saving it to database.
     *
     * @param string $value
     * @return void
     */
    public function format($value)
    {
        return $value;
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
        foreach ($this->requiredAttributes as $prop) {
            if (array_key_exists($prop, $this->attributes)) {
                continue;
            }

            $missing[] = $prop;
        }

        if (empty($missing)) {
            return true;
        }

        throw new MissingAttributeException(sprintf(
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
    protected function setDefaultAttributes()
    {
        foreach ($this->defaultAttributes as $name => $value) {
            $this->setAttribute($name, $value);
        }

        foreach (get_class_methods($this) as $method) {
            if ($method == 'setAttribute') {
                continue;
            }
            if (!Str::startsWith($method, 'set')) {
                continue;
            }
            if (!Str::endsWith($method, 'Attribute')) {
                continue;
            }

            $this->setAttribute(
                $this->getSetterAttributeName($method),
                call_user_func_array([$this, $method], [])
            );
        }
    }

    /**
     * Remove available attribute.
     *
     * @param string $attribute
     * @return void
     */
    public function removeAvailableAttribute(string $attribute)
    {
        if (($key = array_search($attribute, $this->availableAttributes)) !== false) {
            unset($this->availableAttributes[$key]);
        }
    }

    /**
     * Get attribute name from setter method name.
     * 
     * setNameAttribute => name
     * setCamelCaseAttribute => camelCase
     *
     * @param string $method
     * @return string
     */
    protected function getSetterAttributeName(string $method)
    {
        return lcfirst(
            Str::replaceLast(
                'Attribute',
                '',
                Str::replaceFirst(
                    'set',
                    '',
                    $method
                )
            )
        );
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
     * @throws \Fjord\Exceptions\MethodNotFoundException
     */
    protected function methodNotFound($method)
    {
        throw new MethodNotFoundException(
            sprintf(
                'The %s method is not found for the %s field. Available field attributes: %s.',
                $method,
                class_basename(static::class),
                implode(', ', $this->availableAttributes)
            ),
            [
                'function' => '__call',
                'class' => self::class
            ]
        );
    }


    /**
     * Get avaliable attributes.
     *
     * @return array
     */
    public function getAvailableAttributes()
    {
        return $this->availableAttributes;
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
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
        return $this->requiredAttributes;
    }

    /**
     * Merge properties from extensions.
     *
     * @return void
     */
    protected function mergeExtensionProperties()
    {
        foreach ($this->mergeProperties as $property) {
            foreach ($this->extensions as $extension) {
                if (!property_exists($extension, $property)) {
                    continue;
                }

                $this->{$property} = array_merge(
                    $this->{$property},
                    $extension->{$property}
                );
            }
        }
    }

    /**
     * Merge properties from traits.
     *
     * @return void
     */
    protected function mergeTraitProperties()
    {
        foreach (array_keys(get_object_vars($this)) as $property) {
            if (Str::startsWith($property, 'available') && Str::endsWith($property, 'Attributes')) {
                $this->availableAttributes = array_merge(
                    $this->availableAttributes,
                    $this->{$property}
                );
            }
            if (Str::startsWith($property, 'required') && Str::endsWith($property, 'Attributes')) {
                $this->requiredAttributes = array_merge(
                    $this->requiredAttributes,
                    $this->{$property}
                );
            }
            if (Str::startsWith($property, 'default') && Str::endsWith($property, 'Attributes')) {
                $this->defaultAttributes = array_merge(
                    $this->defaultAttributes,
                    $this->{$property}
                );
            }
        }
    }

    /**
     * Get defaults.
     *
     * @return array
     */
    public function getDefaultAttributes()
    {
        return $this->defaultAttributes;
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
     * Check if attribute is available.
     *
     * @param string $attribute
     * @return boolean
     */
    public function isAttributeAvailable(string $attribute)
    {
        return in_array($attribute, $this->availableAttributes);
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
        if ($this->isAttributeAvailable($method)) {
            return $this->setAttribute($method, ...$params);
        }

        foreach ($this->extensions as $extension) {
            if (method_exists($extension, $method)) {
                return $this->forwardCallTo($extension, $method, $params);
            }
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
