<?php

namespace Fjord\Crud;

use Closure;
use Exception;
use Fjord\Support\VueProp;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Fjord\Exceptions\MethodNotFoundException;
use Fjord\Exceptions\InvalidArgumentException;
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
    protected $formInstance;

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
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

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
        $this->model = $model;
        $this->formInstance = $form;

        $this->validateFieldId($model, $id);

        $this->setAttribute('id', $id);
        $this->setAttribute('local_key', $id);
        $this->setAttribute('route_prefix', $routePrefix);
        $this->setAttribute('component', $this->component);
        $this->setAttribute('readonly', false);
        $this->setAttribute('class', '');

        $this->setDefaultsFromClassMethods();
        $this->setDefaultAttributes();
        $this->mergeRequiredAttributes();
    }

    /**
     * Validate field id.
     *
     * @param string $model
     * @param string $id
     * @return void
     * 
     * @throws \Fjord\Exceptions\InvalidArgumentException
     */
    protected function validateFieldId($model, $id)
    {
        if ($id == 'media') {
            throw new InvalidArgumentException('The field id cannot be "media".', [
                'function' => '__call'
            ]);
        }
    }

    /**
     * Get model class.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get field title.
     *
     * @return string
     */
    public function getTitle()
    {
        return collect(explode('_', $this->getAttribute('id')))->map(function ($word) {
            return ucfirst($word);
        })->implode(' ');
    }

    /**
     * Fill model.
     *
     * @param mixed $model
     * @param string $attributeName
     * @param mixed $attributeValue
     * @return void
     */
    public function fillModel($model, $attributeName, $attributeValue)
    {
        return;
    }

    /**
     * Set readonly attribute.
     *
     * @param boolean $readonly
     * @return $this
     */
    public function readonly(bool $readonly = true)
    {
        $this->setAttribute('readonly', $readonly);

        return $this;
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
     * Merge required properties to allow defining required attributes in traits.
     *
     * @return void
     */
    public function mergeRequiredAttributes()
    {
        foreach (get_object_vars($this) as $propertyName => $propertyValue) {
            if ($propertyName == 'required') {
                continue;
            }
            if (!Str::endsWith($propertyName, 'Required')) {
                continue;
            }
            $this->required = array_merge($this->required, $propertyValue);
        }
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
            throw new InvalidArgumentException("Slot {$slot} does not exist for Field {$field}");
        }

        $this->{$this->getSlotMethodName($slot)}($component);

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
        return in_array(
            $this->getSlotMethodName($slot),
            get_class_methods($this)
        );
    }

    /**
     * Get slot method name.
     *
     * @param string $slot
     * @return string
     */
    protected function getSlotMethodName(string $slot)
    {
        return Str::camel("{$slot}_slot");
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
     * Set default attributes from class method.
     *
     * @return void
     */
    public function setDefaultsFromClassMethods()
    {
        foreach (get_class_methods($this) as $method) {
            if (!Str::startsWith($method, 'set') || !Str::endsWith($method, 'Default')) {
                continue;
            }
            $attributeName = $this->getDefaultSetterAttributeName($method);
            $attributeValue = $this->{$method}();
            $this->setAttribute($attributeName, $attributeValue);
        }
    }

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        // Set the field default attributes in here.

        // $this->something('value');
        // or:
        // $this->setAttribute('something', 'value');
    }

    /**
     * Get attribute name from setter method name.
     * 
     * setNameDefault => name
     * setCamelCaseDefault => camelCase
     *
     * @param string $method
     * @return string
     */
    protected function getDefaultSetterAttributeName(string $method)
    {
        return lcfirst(
            Str::replaceFirst(
                'set',
                '',
                Str::replaceLast(
                    'Default',
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
                'The %s method is not found for the %s field.',
                $method,
                class_basename(static::class),
            ),
            [
                'function' => '__call',
                'class' => self::class
            ]
        );
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
    public function getRequired()
    {
        return $this->required;
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
     * Render field.
     *
     * @return array
     */
    public function render(): array
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
        /*
        // TODO: Discuss field extensions.
        foreach ($this->extensions as $extension) {
            if (method_exists($extension, $method)) {
                return $this->forwardCallTo($extension, $method, $params);
            }
        }
        */

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
