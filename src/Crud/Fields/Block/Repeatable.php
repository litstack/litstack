<?php

namespace Fjord\Crud\Fields\Block;

use Closure;
use Fjord\Vue\Table;
use Fjord\Crud\BaseForm;
use Fjord\Support\VueProp;
use Fjord\Crud\Models\FormBlock;
use Illuminate\Support\Traits\ForwardsCalls;

class Repeatable extends VueProp
{
    use ForwardsCalls;

    /**
     * View name.
     *
     * @var string|null
     */
    protected $viewName;

    /**
     * Blade x component name.
     *
     * @var string|null
     */
    protected $xComponent;

    protected $form;

    protected $preview;

    /**
     * Create new Repeatable instance.
     */
    public function __construct(Block $field, string $name)
    {
        $this->field = $field;
        $this->name = $name;
    }

    protected function getRoutePrefix()
    {
        return strip_slashes("{$this->field->route_prefix}/block");
    }

    /**
     * Configure repeatable form.
     *
     * @param Closure $closure
     * @return $this
     */
    public function form(Closure $closure)
    {
        $form = new BlockForm(FormBlock::class);

        $form->setRoutePrefix($this->getRoutePrefix());

        $form->afterRegisteringField(function ($field) {
            $field->setAttribute('params', [
                'field_id' => $this->field->id,
                'repeatable_id' => null,
                'type' => $this->name,
            ]);
        });

        $this->preview = new Table;

        $closure($form, $this->preview);

        $this->form = $form;

        return $this;
    }

    /**
     * To array.
     *
     * @return array
     */
    public function render(): array
    {
        return [
            'form' => $this->form->toArray(),
            'preview' => $this->preview->toArray()
        ];
    }

    /**
     * Determine if repeatable has Blade x component.
     *
     * @return boolean
     */
    public function hasX()
    {
        return $this->xComponent != null;
    }

    /**
     * Get x component.
     *
     * @return string|null
     */
    public function getX()
    {
        return $this->xComponent;
    }

    /**
     * Set blade x component.
     *
     * @param string $component
     * @return $this
     */
    public function x(string $component)
    {
        $this->xComponent = $component;

        return $this;
    }

    /**
     * Add blade x component.
     *
     * @param string $view
     * @return $this
     */
    public function view(string $name)
    {
        $this->viewName = $name;

        return $this;
    }

    /**
     * Determine if repeatable has view.
     *
     * @return boolean
     */
    public function hasView()
    {
        return $this->viewName != null;
    }

    /**
     * Get view name.
     *
     * @return string|null
     */
    public function getView()
    {
        return $this->viewName;
    }

    /**
     * Get form instance.
     *
     * @return BaseForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Get registered fields.
     *
     * @return Collection
     */
    public function getRegisteredFields()
    {
        return $this->form ? $this->form->getRegisteredFields() : collect([]);
    }

    /**
     * Handle dynamic method calls to the repeatable.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->form, $method, $parameters);
    }
}
