<?php

namespace Fjord\Crud;

use Closure;
use Fjord\Crud\Fields\Block\Block;
use Fjord\Crud\Fields\Component;
use Fjord\Crud\Fields\Relations\BelongsTo;
use Fjord\Crud\Fields\Relations\BelongsToMany;
use Fjord\Crud\Fields\Relations\HasMany;
use Fjord\Crud\Fields\Relations\HasOne;
use Fjord\Crud\Fields\Relations\MorphMany;
use Fjord\Crud\Fields\Relations\MorphOne;
use Fjord\Crud\Fields\Relations\MorphToMany;
use Fjord\Crud\Fields\Relations\MorphToRegistrar;
use Fjord\Crud\Models\FormField;
use Fjord\Exceptions\Traceable\BadMethodCallException;
use Fjord\Support\Facades\Fjord;
use Fjord\Support\Facades\Form as FormFacade;
use Fjord\Support\VueProp;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;

class BaseForm extends VueProp
{
    use Macroable {
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
     * List of closure's that get called after registering a field.
     *
     * @var array
     */
    protected $registerFieldHooks = [];

    /**
     * Create new BaseForm instance.
     *
     * @return void
     */
    public function __construct(string $model)
    {
        $this->model = $model;

        $this->registeredFields = collect([]);
    }

    /**
     * Is registering component in wrapper.
     *
     * @return bool
     */
    public function inWrapper()
    {
        return $this->wrapper !== null;
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
     * Create wrapper.
     *
     * @param Component|string $component
     *
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
     * @param mixed $wrapper
     * @param mixed $closure
     *
     * @return Component
     */
    public function registerWrapper($wrapper, $closure)
    {
        if ($this->inWrapper()) {
            $this->wrapper
                ->component($wrapper);

            $this->wrapperStack[] = $this->wrapper;
        }

        $this->wrapper = $wrapper;
        $closure($this);
        $this->wrapper = ! empty($this->wrapperStack)
            ? array_pop($this->wrapperStack)
            : null;

        return $wrapper->wrapperComponent;
    }

    /**
     * Formfield group.
     *
     * @param Closure $closure
     *
     * @return Component
     */
    public function group(Closure $closure)
    {
        return $this->wrapper('fj-field-wrapper-group', function () use ($closure) {
            $closure($this);
        });
    }

    /**
     * Register column wrapper.
     *
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
     * @param CrudCreateRequest|CrudUpdateRequest $request
     * @param string|null                         $type
     *
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
     * @param mixed  $field
     * @param string $id
     * @param array  $params
     *
     * @return Field $field
     */
    public function registerField($field, string $id, $params = [])
    {
        if ($field instanceof Field) {
            $fieldInstance = $field;
        } else {
            $fieldInstance = new $field($id, $this->model, $this->routePrefix, $this);
        }

        $this->registrar = $fieldInstance;

        if ($fieldInstance->register()) {
            $this->registeredFields[] = $fieldInstance;
        }

        if ($this->inWrapper() && ! $this->col && $fieldInstance->register()) {
            $this->wrapper
                ->component('fj-field')
                ->prop('field', $fieldInstance);
        }

        foreach ($this->registerFieldHooks as $hook) {
            $hook($fieldInstance);
        }

        return $fieldInstance;
    }

    /**
     * Add after registering field hook.
     *
     * @return void
     */
    public function afterRegisteringField(Closure $closure)
    {
        $this->registerFieldHooks[] = $closure;
    }

    /**
     * Register new Relation.
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function relation(string $name)
    {
        if (FormField::class === $this->model) {
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
     * Add Vue component field.
     *
     * @return \Fjord\Vue\Component
     */
    public function component(string $component)
    {
        return $this->registerField(FormFacade::getField('component'), $component);
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
     *
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
     * @param string $repeatable
     *
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
     * @param string $repeatable
     *
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
     * Get attributes.
     */
    public function render(): array
    {
        foreach ($this->registeredFields as $field) {
            $field->checkComplete();
        }

        return [
            'fields' => $this->registeredFields,
        ];
    }

    /**
     * Get new wrapper.
     *
     * @param Component|string $component
     *
     * @return component
     */
    protected function getNewWrapper($component)
    {
        if (\is_string($component)) {
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
     * Call form method.
     *
     * @param string $method
     * @param array  $params
     *
     * @throws \Fjord\Exceptions\Traceable\BadMethodCallException
     *
     * @return void
     */
    public function __call($method, $params = [])
    {
        if (FormFacade::fieldExists($method)) {
            return $this->registerField(FormFacade::getField($method), ...$params);
        }

        if (static::hasMacro($method)) {
            return $this->macroCall($method, $params);
        }

        throw new BadMethodCallException(
            sprintf('Call to undefined method %s::%s()', static::class, $method),
            ['function' => '__call', 'class' => self::class]
        );
    }
}
