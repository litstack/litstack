<?php

namespace Fjord\Crud;

use Closure;
use Fjord\Crud\Fields\Component;
use Fjord\Exceptions\Traceable\InvalidArgumentException;
use Fjord\Page\Page;
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
    protected $rootComponent = 'fj-crud-form-page';

    /**
     * Form instance.
     *
     * @var BaseForm
     */
    protected $form;

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
            ->component('fj-field')
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
        return $this->wrapper('fj-field-wrapper-group', function () use ($closure) {
            $closure($this);
        });
    }

    /**
     * Register new Field.
     *
     * @param  mixed  $field
     * @param  string $id
     * @param  array  $params
     * @return Field  $field
     */
    // public function registerField($field, string $id, $params = [])
    // {
    //     if (! $this->inWrapper()) {
    //         throw new InvalidArgumentException('Fields must be registered inside a wrapper.', [
    //             'function' => '__call',
    //         ]);
    //     }

    //     return $this->form->registerField($field, $id, $params);
    // }

    /**
     * Is registering component in card.
     *
     * @return bool
     */
    public function inCard()
    {
        return $this->inCard;
    }

    // public function wrapper($component, Closure $closure)
    // {
    //     $wrapper = parent::wrapper($component, function ($self, $wrapper) use ($closure) {
    //         $wrapper = $this->form->wrapper($wrapper, function ($form, $wrapper) use ($closure) {
    //             $closure($form, $wrapper);

    //             array_pop($this->components);

    //             $this->components[] = $wrapper;
    //             $this->wrapper = $wrapper;
    //         });
    //     });

    //     //dd($this, $wrapper->title('abc'));

    //     return $wrapper;
    // }

    /**
     * Add Vue component.
     *
     * @param  string               $component
     * @return \Fjord\Vue\Component
     */
    public function component($component)
    {
        if ($this->inCard()) {
            return $this->form->component($component);
        }

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
        $info = $this->component('fj-info')->title($title);

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
        return $this->wrapper('fj-field-wrapper-card', function ($form) use ($closure) {
            $this->inCard = true;
            $closure($this);
            $this->inCard = false;
        });
    }

    /**
     * Get CrudForm components.
     *
     * @return array
     */
    // public function getComponents()
    // {

    //     return $this->forwardCallTo($this->form, 'getComponents', []);
    // }

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
        return $this->forwardCallTo($this->form, $method, $parameters);
    }
}
