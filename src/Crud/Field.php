<?php

namespace Fjord\Crud;

use Closure;
use BadMethodCallException;
use Fjord\Crud\Models\FormField;
use Fjord\Application\Config\ConfigItem;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Field extends ConfigItem
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
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'width',
    ];

    /**
     * Available field attributes.
     *
     * @var array
     */
    protected $available = [
        'readonly',
        'cols'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'readonly' => false,
        'cols' => 12
    ];

    /**
     * Create new Field instance.
     *
     * @param string $id
     * @param string $model
     */
    public function __construct(string $id, string $model)
    {
        // Merge available, default and required properties of parent and child 
        // class.
        if (static::class != self::class) {
            $parent = new self($id, $model);
            $this->available = array_merge(
                $parent->getAvailableAttributes(),
                $this->available,
            );
            $this->defaults = array_merge(
                $parent->getDefaults(),
                $this->defaults,
            );
            $this->required = array_merge(
                $parent->getRequiredAttributes(),
                $this->required,
            );
        } else {
            return;
        }

        $this->attributes = collect([]);

        $this->attributes['id'] = $id;
        $this->attributes['local_key'] = $id;
        $this->model = $model;

        if ($this->translatable) {
            $this->available[] = 'translatable';
        }

        $this->setDefaults();
        $this->attributes['component'] = $this->component;
    }

    /**
     * Get value for model FormField.
     *
     * @param FormField $formField
     * @param string $locale
     * @return mixed
     */
    public function getFormFieldValue(FormField $formField, string $locale)
    {
        $value = $formField->translation[$locale] ?? [];

        return $value['value'] ?? null;
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
     * Is field relation.
     *
     * @return boolean
     */
    public function isRelation()
    {
        return false;
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

        $this->attributes['translatable'] = false;
    }

    /**
     * Set field attribute.
     *
     * @param string $name
     * @param string|int|closure $value
     * @return self
     * 
     * @throws MethodNotAllowedHttpException
     */
    public function setAttribute(string $name, $value)
    {
        if ($value instanceof Closure) {
            $value = Closure::bind($value, $this)();
        }

        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Throw a method not allowed HTTP exception.
     *
     * @param  array  $others
     * @param  string  $method
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    protected function methodNotAllowed($method)
    {
        $allowed = $this->getAllowedMethods();
        throw new MethodNotAllowedHttpException(
            $allowed,
            sprintf(
                'The %s method is not supported for this field. Supported methods: %s.',
                $method,
                implode(', ', $allowed)
            )
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
    public function toArray()
    {
        return $this->attributes->toArray();
    }

    /**
     * Call field method.
     *
     * @param string $method
     * @param array $params
     * @return static|void
     * 
     * @throws MethodNotAllowedHttpException
     */
    public function __call($method, $params = [])
    {
        if (in_array($method, $this->available)) {
            return $this->setAttribute($method, ...$params);
        }

        $this->methodNotAllowed($method);
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
