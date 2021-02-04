<?php

namespace Ignite\Crud;

use Closure;
use Ignite\Contracts\Crud\Form;
use Ignite\Crud\Fields\Block\Block;
use Ignite\Crud\Fields\Component;
use Ignite\Crud\Fields\Relations\BelongsTo;
use Ignite\Crud\Fields\Relations\BelongsToMany;
use Ignite\Crud\Fields\Relations\HasMany;
use Ignite\Crud\Fields\Relations\HasOne;
use Ignite\Crud\Fields\Relations\MorphMany;
use Ignite\Crud\Fields\Relations\MorphOne;
use Ignite\Crud\Fields\Relations\MorphToMany;
use Ignite\Crud\Fields\Relations\MorphToRegistrar;
use Ignite\Curd\Models\Form as FormModel;
use Ignite\Exceptions\Traceable\BadMethodCallException;
use Ignite\Page\BasePage;
use Ignite\Support\Facades\Form as FormFacade;
use Ignite\Support\Facades\Lit;
use Ignite\Vue\Traits\RenderableAsProp;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;

class BaseForm extends BasePage implements Form, Arrayable, Jsonable
{
    use RenderableAsProp,
        Macroable {
            __call as macroCall;
        }

    /**
     * Available relations.
     *
     * @var array
     */
    protected $relations = [
        \Illuminate\Database\Eloquent\Relations\BelongsToMany::class => BelongsToMany::class,
        \Illuminate\Database\Eloquent\Relations\BelongsTo::class     => BelongsTo::class,
        \Illuminate\Database\Eloquent\Relations\MorphOne::class      => MorphOne::class,
        \Illuminate\Database\Eloquent\Relations\MorphTo::class       => MorphToRegistrar::class,
        \Illuminate\Database\Eloquent\Relations\MorphToMany::class   => MorphToMany::class,
        \Illuminate\Database\Eloquent\Relations\MorphMany::class     => MorphMany::class,
        \Illuminate\Database\Eloquent\Relations\HasMany::class       => HasMany::class,
        \Illuminate\Database\Eloquent\Relations\HasOne::class        => HasOne::class,
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
     * Registering field hooks.
     *
     * @var array
     */
    protected $registeringFieldHooks = [];

    /**
     * List of closure's that get called after registering a field.
     *
     * @var array
     */
    protected $registerFieldHooks = [];

    /**
     * Create new BaseForm instance.
     *
     * @param  string $model
     * @return void
     */
    public function __construct(string $model)
    {
        $this->model = $model;
        $this->registeredFields = collect([]);
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
     * Add Vue component field.
     *
     * @param  string|Component      $component
     * @return \Ignite\Vue\Component
     */
    public function component($component)
    {
        return $this->registerField(FormFacade::getField('component'), $component);

        if ($this->inWrapper()) {
            parent::component($component->comp);
        }

        return $component;
    }

    /**
     * Add group wrapper.
     *
     * @param  Closure   $closure
     * @return Component
     */
    public function group(Closure $closure)
    {
        return $this->wrapper('lit-field-wrapper-group', function () use ($closure) {
            $closure($this);
        });
    }

    /**
     * Get rules for request.
     *
     * @param  CrudCreateRequest|CrudUpdateRequest $request
     * @param  string|null                         $type
     * @return array
     */
    public function getRules($type = null)
    {
        $rules = [];
        foreach ($this->registeredFields as $field) {
            if (! method_exists($field, 'getRules')) {
                continue;
            }
            $fieldRules = $field->getRules($type);
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
     * @param  string $prefix
     * @return void
     */
    public function setRoutePrefix($prefix)
    {
        if (Str::startsWith($prefix, Lit::url(''))) {
            $prefix = Str::replaceFirst(Lit::url(''), '', $prefix);
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
     * @param  mixed  $field
     * @param  string $id
     * @param  array  $params
     * @return Field  $field
     */
    public function registerField($field, ...$params)
    {
        if ($field instanceof Field) {
            $fieldInstance = $field;
        } else {
            $fieldInstance = new $field(...$params);
            $fieldInstance->setModel($this->model);
            $fieldInstance->setParentForm($this);
            $fieldInstance->setRoutePrefix($this->routePrefix);
        }

        foreach ($this->registeringFieldHooks as $hook) {
            $hook($fieldInstance);
        }

        $this->registrar = $fieldInstance;

        if ($fieldInstance->shouldBeRegistered()) {
            $this->registeredFields[] = $fieldInstance;
        }

        if ($this->inWrapper() && $fieldInstance->shouldBeRegistered()) {
            $this->wrapper
                ->component('lit-field')
                ->prop('field', $fieldInstance);
        }

        foreach ($this->registerFieldHooks as $hook) {
            $hook($fieldInstance);
        }

        return $fieldInstance;
    }

    /**
     * Before registering field hook.
     *
     * @param  Closure $closure
     * @return void
     */
    public function registering(Closure $closure)
    {
        $this->registeringFieldHooks[] = $closure;
    }

    /**
     * Add after registering field hook.
     *
     * @param  Closure $closure
     * @return void
     */
    public function registered(Closure $closure)
    {
        $this->registerFieldHooks[] = $closure;

        foreach ($this->registeredFields as $field) {
            $closure($field);
        }
    }

    /**
     * Register new Relation.
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function relation(string $name)
    {
        if (FormModel::class === $this->model) {
            throw new InvalidArgumentException('Laravel relations are not available in Forms. Use fields oneRelation or manyRelation instead.');
        }

        $relationType = \get_class((new $this->model())->{$name}());

        if (\array_key_exists($relationType, $this->relations)) {
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
     * Get registered fields.
     *
     * @return array
     */
    public function getRegisteredFields()
    {
        return $this->registeredFields;
    }

    /**
     * Find registered field.
     *
     * @param  string     $fieldId
     * @return Field|void
     */
    public function findField($fieldId)
    {
        foreach ($this->registeredFields as $field) {
            if ($field instanceof Component) {
                continue;
            }

            if ($field->id === $fieldId) {
                return $field;
            }
        }
    }

    /**
     * Check if form has field.
     *
     * @return bool
     */
    public function hasField(string $fieldId)
    {
        if ($this->findField($fieldId)) {
            return true;
        }

        return false;
    }

    /**
     * Check if field with form exists.
     *
     * @param  string $repeatable
     * @return bool
     */
    public function hasForm(string $name, string $repeatable = null)
    {
        if (! $field = $this->findField($name)) {
            return false;
        }

        if (! $field instanceof Block) {
            return method_exists($field, 'form');
        }

        if (! $repeatable) {
            return false;
        }

        return $field->hasRepeatable($repeatable);
    }

    /**
     * Get form from field.
     *
     * @param  string   $repeatable
     * @return BaseForm
     */
    public function getForm(string $name, string $repeatable = null)
    {
        if (! $this->hasForm($name, $repeatable)) {
            return;
        }

        if (! $field = $this->findField($name)) {
            return;
        }

        if (! $field instanceof Block) {
            return $field->form;
        }

        return $field->getRepeatable($repeatable)->getForm();
    }

    /**
     * Render Form.
     *
     * @return array
     */
    public function render(): array
    {
        foreach ($this->registeredFields as $field) {
            $field->checkComplete();
        }

        return array_merge(parent::render(), [
            'fields' => $this->registeredFields,
        ]);
    }

    /**
     * Call form method.
     *
     * @param  string $method
     * @param  array  $parameters
     * @return void
     *
     * @throws \Ignite\Exceptions\Traceable\BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (FormFacade::fieldExists($method)) {
            return $this->registerField(FormFacade::getField($method), ...$parameters);
        }

        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        throw new BadMethodCallException(
            sprintf('Call to undefined method %s::%s()', static::class, $method),
            ['function' => '__call', 'class' => self::class]
        );
    }
}
