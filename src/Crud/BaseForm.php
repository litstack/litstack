<?php

namespace Fjord\Crud;

use Closure;

use Fjord\Support\VueProp;
use Fjord\Crud\Fields\Code;
use Fjord\Crud\Fields\Icon;
use Illuminate\Support\Str;
use Fjord\Crud\Fields\Input;
use Fjord\Crud\Fields\Modal;
use Fjord\Crud\Fields\Range;
use Fjord\Crud\Fields\Select;
use InvalidArgumentException;
use Fjord\Crud\Fields\Boolean;
use Fjord\Crud\Fields\Wysiwyg;
use Fjord\Crud\Fields\Datetime;
use Fjord\Crud\Fields\Password;
use Fjord\Crud\Fields\Textarea;
use Fjord\Crud\Fields\Component;
use Fjord\Crud\Models\FormField;
use Fjord\Support\Facades\Fjord;
use Fjord\Crud\Fields\Checkboxes;
use Fjord\Crud\Fields\Media\Image;
use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Crud\Fields\Relations\HasOne;
use Fjord\Crud\Fields\Relations\HasMany;
use Illuminate\Support\Traits\Macroable;
use Fjord\Crud\Fields\Relations\MorphOne;
use Fjord\Crud\Fields\Relations\BelongsTo;
use Fjord\Crud\Fields\Relations\MorphMany;
use Fjord\Crud\Fields\Relations\MorphToMany;
use Fjord\Crud\Fields\Relations\OneRelation;
use Fjord\Crud\Fields\Relations\ManyRelation;
use Fjord\Exceptions\MethodNotFoundException;
use Fjord\Crud\Fields\Relations\BelongsToMany;
use Fjord\Crud\Fields\Relations\MorphToRegistrar;


class BaseForm extends VueProp
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * Available fields.
     *
     * @var array
     */
    protected $fields = [
        'input' => Input::class,
        'password' => Password::class,
        'select' => Select::class,
        'boolean' => Boolean::class,
        'code' => Code::class,
        'icon' => Icon::class,
        'datetime' => Datetime::class,
        'dt' => Datetime::class,
        'checkboxes' => Checkboxes::class,
        'range' => Range::class,
        'textarea' => Textarea::class,
        'text' => Textarea::class,
        'wysiwyg' => Wysiwyg::class,
        'blocks' => Blocks::class,
        'image' => Image::class,
        'modal' => Modal::class,
        'component' => Component::class,
        'oneRelation' => OneRelation::class,
        'manyRelation' => ManyRelation::class,
    ];

    /**
     * Available relations.
     *
     * @var array
     */
    protected $relations = [
        \Illuminate\Database\Eloquent\Relations\BelongsToMany::class => BelongsToMany::class,
        \Illuminate\Database\Eloquent\Relations\BelongsTo::class => BelongsTo::class,
        \Illuminate\Database\Eloquent\Relations\MorphOne::class => MorphOne::class,
        \Illuminate\Database\Eloquent\Relations\MorphTo::class => MorphToRegistrar::class,
        \Illuminate\Database\Eloquent\Relations\MorphToMany::class => MorphToMany::class,
        \Illuminate\Database\Eloquent\Relations\MorphMany::class => MorphMany::class,
        \Illuminate\Database\Eloquent\Relations\HasMany::class => HasMany::class,
        \Illuminate\Database\Eloquent\Relations\HasOne::class => HasOne::class,
    ];

    /**
     * Model class.
     *
     * @var string
     */
    protected $model;

    /**
     * Field that is being registered is stored in here. When the next 
     * field is called this field will be checked for required properties. 
     *
     * @var Field
     */
    protected $registrar;

    /**
     * Registered fields.
     *
     * @var array 
     */
    protected $registeredFields = [];

    /**
     * Route prefix of form.
     *
     * @var string
     */
    protected $routePrefix;

    /**
     * Current col that Fields should be registered to.
     *
     * @var Component
     */
    protected $col;

    /**
     * Current wrapper component.
     *
     * @var Component
     */
    protected $wrapper;

    /**
     * Wrapper component stack.
     *
     * @var array
     */
    protected $wrapperStack = [];

    /**
     * Create new BaseForm instance.
     *
     * @param string $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;

        $this->registeredFields = collect([]);
    }

    /**
     * Is registering component in wrapper.
     *
     * @return boolean
     */
    public function inWrapper()
    {
        return $this->wrapper != null;
    }

    /**
     * Get current card.
     *
     * @return array $card
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

    /**
     * Get new wrapper
     * 
     * @param string|Component $component
     * @return component
     */
    protected function getNewWrapper($component)
    {
        if (is_string($component)) {
            $component = component($component);
        }

        if ($this->inWrapper()) {
            $wrapper = component('fj-field-wrapper');
        } else {
            $wrapper = $this->component('fj-field-wrapper');
        }

        return $wrapper->wrapperComponent($component);
    }

    /**
     * Create wrapper.
     *
     * @param string|Component $component
     * @param Closure $closure
     * @return self
     */
    public function wrapper($component, Closure $closure)
    {
        $newWrapper = $this->getNewWrapper($component);

        return $this->registerWrapper($newWrapper, $closure);
    }

    /**
     * Register new wrapper.
     * 
     * @return Component
     */
    public function registerWrapper($wrapper, $closure)
    {
        if ($this->inWrapper()) {
            /*
            $newWrapper = $this->component('fj-field-wrapper')
                ->wrapper($component);
        } else {
            $newWrapper = component('fj-field-wrapper')
                ->wrapper($component);
                */

            $this->wrapper
                ->component($wrapper);

            $this->wrapperStack[] = $this->wrapper;
        }

        $this->wrapper = $wrapper;
        $closure($this);
        $this->wrapper = !empty($this->wrapperStack)
            ? array_pop($this->wrapperStack)
            : null;

        //dd($wrapper->wrapperComponent);

        return $wrapper->wrapperComponent;
    }

    /**
     * Register column wrapper.
     *
     * @param int $cols
     * @param Closure $closure
     * @return void
     */
    public function col(int $cols, Closure $closure)
    {
        return $this->wrapper('fj-col', function ($form) use ($closure) {
            $this->wrapper('b-row', function ($form) use ($closure) {
                $closure($this);
            });
        })->prop('cols', $cols);
    }

    /**
     * Get rules for request.
     *
     * @param CrudUpdateRequest|CrudCreateRequest $request
     * @return array
     */
    public function getRules($request)
    {
        $rules = [];
        foreach ($this->registeredFields as $field) {
            if (!method_exists($field, 'getRules')) {
                continue;
            }
            $fieldRules = $field->getRules($request);
            if ($field->translatable) {
                // Attach rules for translatable fields.
                foreach (config('translatable.locales') as $locale) {
                    $rules["{$locale}.{$field->local_key}"] = $fieldRules;
                }
            } else {
                $rules[$field->local_key] = $fieldRules;
            }
        }
        return $rules;
    }

    /**
     * Set form route prefix.
     *
     * @param string $prefix
     * @return void
     */
    public function setRoutePrefix(string $prefix)
    {
        if (Str::startsWith($prefix, Fjord::url(''))) {
            $prefix = Str::replaceFirst(Fjord::url(''), '', $prefix);
        }
        $this->routePrefix = $prefix;
    }

    /**
     * Get route prefix.
     *
     * @return string
     */
    public function getRoutePrefix()
    {
        return $this->routePrefix;
    }

    /**
     * Register new Field.
     *
     * @param mixed $field
     * @param string $id
     * @param array $params
     * @return Field $field
     */
    public function registerField($field, string $id, $params = [])
    {
        if ($this->registrar) {
            // Check if all required properties are set.
            $this->registrar->checkComplete();
        }

        $fieldInstance = new $field($id, $this->model, $this->routePrefix, $this);

        if ($fieldInstance->isRelation()) {
            $fieldInstance->setAttribute('many', $fieldInstance instanceof ManyRelationField);
        }

        $this->registrar = $fieldInstance;

        if ($fieldInstance->register()) {
            $this->registeredFields[] = $fieldInstance;
        }

        if ($this->inWrapper() && !$this->col && $fieldInstance->register()) {
            $this->wrapper
                ->component('fj-field')
                ->prop('field', $fieldInstance);
        }

        return $fieldInstance;
    }

    /**
     * Register new Relation.
     *
     * @param string $name
     * @return mixed
     * 
     * @throws \InvalidArgumentException
     */
    public function relation(string $name)
    {
        if ($this->model == FormField::class) {
            throw new InvalidArgumentException("Laravel relations are not available in Forms. Use fields oneRelation or manyRelation instead.");
        }

        $relationType = get_class((new $this->model)->$name());

        if (array_key_exists($relationType, $this->relations)) {
            return $this->registerField($this->relations[$relationType], $name);
        }

        throw new InvalidArgumentException(sprintf(
            'Relation %s not supported. Supported relations: %s',
            lcfirst(class_basename($relationType)),
            implode(', ', collect(array_keys($this->relations))->map(function ($relation) {
                return lcfirst(class_basename($relation));
            })->toArray())
        ));
    }

    /**
     * Add Vue component field.
     *
     * @param string $component
     * @return \Fjord\Vue\Component
     */
    public function component(string $component)
    {
        return $this->registerField($this->fields['component'], $component);
    }

    /**
     * Get registered fields.
     *
     * @return void
     */
    public function getRegisteredFields()
    {
        return $this->registeredFields;
    }

    /**
     * Find registered field.
     *
     * @param string $fieldId
     * @return Field|void
     */
    public function findField(string $fieldId)
    {
        foreach ($this->registeredFields as $field) {
            if ($field instanceof Component) {
                continue;
            }

            if ($field->id == $fieldId) {
                return $field;
            }
        }
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function getArray(): array
    {
        return [
            'fields' => $this->registeredFields
        ];
    }

    /**
     * To array.
     *
     * @return array
     */
    public function toArray()
    {
        if ($this->registrar) {
            $this->registrar->checkComplete();
        }

        return parent::toArray();
    }

    /**
     * Throw a method not allowed HTTP exception.
     *
     * @param  array  $others
     * @param  string  $method
     * @return void
     *
     * @throws \Fjord\Exceptions\MethodNotFoundException
     */
    protected function methodNotAllowed($method)
    {
        throw new MethodNotFoundException(
            sprintf(
                "The %s method is not found for this form. Supported fields: %s.",
                $method,
                implode(', ', array_merge(['relation'], array_keys($this->fields))),
            ),
            [
                'function' => '__call',
                'class' => self::class
            ]
        );
    }

    /**
     * Call form method.
     *
     * @param string $method
     * @param array $params
     * @return void
     * 
     * @throws \Fjord\Exceptions\MethodNotFoundException
     */
    public function __call($method, $params = [])
    {
        if (array_key_exists($method, $this->fields)) {
            return $this->registerField($this->fields[$method], ...$params);
        }

        if (static::hasMacro($method)) {
            return $this->macroCall($method, $params);
        }

        $this->methodNotAllowed($method);
    }
}
