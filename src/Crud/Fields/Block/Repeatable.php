<?php

namespace Ignite\Crud\Fields\Block;

use Closure;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\View\Compilers\ComponentTagCompiler;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Models\Repeatable as RepeatableModel;
use Ignite\Page\Table\ColumnBuilder;
use Ignite\Support\VueProp;

class Repeatable extends VueProp
{
    use ForwardsCalls;

    /**
     * View name.
     *
     * @var string|null
     */
    protected $viewName;

    protected $viewData = [];

    /**
     * Blade x component name.
     *
     * @var string|null
     */
    protected $xComponent;

    /**
     * BlockForm instance.
     *
     * @var BlockForm
     */
    protected $form;

    /**
     * Preview.
     *
     * @var ColumnBuilder
     */
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
        return Str::finish("{$this->field->route_prefix}", '/block');

        return strip_slashes("{$this->field->route_prefix}/block");
    }

    /**
     * Configure repeatable form.
     *
     * @param Closure $closure
     *
     * @return $this
     */
    public function form(Closure $closure)
    {
        $form = new BlockForm(RepeatableModel::class);

        $form->setRoutePrefix($this->getRoutePrefix());

        $form->registered(function ($field) {
            $field->setAttribute('params', [
                'field_id'        => $this->field->id,
                'repeatable_id'   => null,
                'repeatable_type' => $this->name,
            ]);
        });

        $this->preview = new ColumnBuilder;

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
            'form'    => $this->form,
            'preview' => $this->preview,
        ];
    }

    /**
     * Determine if repeatable has Blade x component.
     *
     * @return bool
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
     * Resolves Blade x component.
     *
     * @param  array       $params
     * @return string|null
     */
    public function getXClass(...$params)
    {
        if (! $this->xComponent) {
            return;
        }

        return call_unaccessible_method(
            app(ComponentTagCompiler::class),
            'componentClass',
            [$this->xComponent]
        );
    }

    /**
     * Set blade x component.
     *
     * @param  string $component
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
     * @param  string $view
     * @return $this
     */
    public function view(string $name, $data = [])
    {
        $this->viewName = $name;
        $this->viewData = $data;

        return $this;
    }

    /**
     * Determine if repeatable has view.
     *
     * @return bool
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
        return view($this->viewName, $this->viewData);
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
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->form, $method, $parameters);
    }
}
