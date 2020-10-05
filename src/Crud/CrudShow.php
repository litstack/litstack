<?php

namespace Ignite\Crud;

use Closure;
use Ignite\Crud\Fields\Component;
use Ignite\Exceptions\Traceable\InvalidArgumentException;
use Ignite\Page\Page;
use Ignite\Support\Facades\Config;
use Ignite\Support\Vue\ButtonComponent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;

class CrudShow extends Page
{
    use ForwardsCalls,
        Macroable {
            __call as macroCall;
        }

    /**
     * Is registering component in card.
     *
     * @var bool
     */
    protected $inCard = false;

    /**
     * Page root Vue Component.
     *
     * @var string
     */
    protected $rootComponent = 'lit-crud-form-page';

    /**
     * Form instance.
     *
     * @var BaseForm
     */
    protected $form;

    /**
     * Query resolver.
     *
     * @var Closure
     */
    protected $queryResolver;

    /**
     * Create new CrudShow instance.
     */
    public function __construct(BaseForm $form)
    {
        parent::__construct();

        $this->form = $form;

        // Add form lifecycle hooks.
        $this->form->registering(fn ($field) => $this->registeringField($field));
        $this->form->registered(fn ($field) => $this->registeredField($field));
    }

    /**
     * Set query resolver.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function query(Closure $closure)
    {
        $this->queryResolver = $closure;

        return $this;
    }

    /**
     * Resolve query.
     *
     * @param  Builder $query
     * @return void
     */
    public function resolveQuery($query)
    {
        if (! $this->queryResolver instanceof Closure) {
            return;
        }

        call_user_func($this->queryResolver, $query);
    }

    /**
     * Add subpage.
     *
     * @param  string          $config
     * @return ButtonComponent
     */
    public function subPage($config, $icon = null)
    {
        $config = Config::get($config);

        $title = $config->names['plural'];
        $prefix = $config->routePrefix();

        if ($this->isOneRelation($query = $config->controllerInstance()->getQuery())) {
            if (! $model = $query->first()) {
                return;
            }

            $config->setModelInstance($model);
            $title = $config->names()['singular'];
            $prefix = "{$prefix}/{$model->id}";
        }

        return $this->headerLeft()->component(new ButtonComponent)
            ->size('sm')
            ->variant('transparent')
            ->prop('href', lit()->url($prefix))
            ->domProp('innerHTML', $icon ? "{$icon} {$title}" : $title);
    }

    /**
     * Determines if query is instance of a "one relation".
     *
     * @param  Relation $query
     * @return bool
     */
    protected function isOneRelation($query)
    {
        $relations = [
            BelongsTo::class, HasOne::class, MorphOne::class, MorphTo::class,
            HasOneThrough::class,
        ];

        foreach ($relations as $relation) {
            if ($query instanceof $relation) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add crud preview for the given url.
     *
     * @param  string          $url
     * @return ButtonComponent
     */
    public function preview($url)
    {
        if ($url instanceof Closure) {
            $url = $url(app()->getLocale());
        }

        $preview = component('lit-crud-preview')->prop('url', $url);

        return $this->headerRight()
            ->component(new ButtonComponent)
            ->child($preview)
            ->size('sm')
            ->variant('primary');
    }

    /**
     * Resolve action component.
     *
     * @param  \Ignite\Vue\Component $component
     * @return void
     */
    public function resolveAction($component)
    {
        $component->on('run', RunCrudActionEvent::class)
            ->prop('eventData', array_merge(
                $component->getProp('eventData'),
                ['model' => $this->form->getModel()]
            ));
    }

    /**
     * Registering field lifecycle hook.
     *
     * @param  Field $field
     * @return void
     */
    protected function registeringField($field)
    {
        if (! $this->inCard()) {
            throw new InvalidArgumentException('Fields must be registered inside a card.', [
                'function' => '__call',
            ]);
        }
    }

    /**
     * Registered Field lifecycle hook.
     *
     * @param  Field $field
     * @return void
     */
    protected function registeredField($field)
    {
        return $this->wrapper
            ->component('lit-field')
            ->prop('field', $field);
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
     * Is registering component in card.
     *
     * @return bool
     */
    public function inCard()
    {
        return $this->inCard;
    }

    /**
     * Add Vue component.
     *
     * @param  string                $component
     * @return \Ignite\Vue\Component
     */
    public function component($component)
    {
        if ($this->inWrapper()) {
            $component = component($component);

            $this->wrapper->component($component);

            return $component;
        }

        return parent::component($component);
    }

    /**
     * Create a new Card.
     *
     * @param  any  ...$params
     * @return void
     */
    public function info(string $title = '')
    {
        $info = $this->component('lit-info')->title($title);

        if ($this->inCard()) {
            $info->heading('h6');
        }

        return $info;
    }

    /**
     * Create b-card wrapper.
     *
     * @param  int     $cols
     * @param  Closure $closure
     * @return void
     */
    public function card(Closure $closure)
    {
        return parent::card(function ($form) use ($closure) {
            $this->inCard = true;
            $closure($this);
            $this->inCard = false;
        });
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function render(): array
    {
        return array_merge($this->form->render(), parent::render());
    }

    /**
     * Get form instance.
     *
     * @return void
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Call CrudShow method.
     *
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters = [])
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return $this->forwardCallTo($this->form, $method, $parameters);
    }
}
