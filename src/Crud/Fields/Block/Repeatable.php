<?php

namespace Ignite\Crud\Fields\Block;

use Closure;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Field;
use Ignite\Crud\Fields\Relations\LaravelRelationField;
use Ignite\Crud\Models\Repeatable as RepeatableModel;
use Ignite\Page\Table\ColumnBuilder;
use Ignite\Support\VueProp;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\View\Compilers\ComponentTagCompiler;
use InvalidArgumentException;

class Repeatable extends VueProp
{
    use ForwardsCalls;

    /**
     * Repeatable type.
     *
     * @var string
     */
    protected $type;

    /**
     * The represantive view of the repeatable.
     *
     * @var string
     */
    protected $view;

    /**
     * View data.
     *
     * @var array
     */
    protected $viewData = [];

    /**
     * Blade x component name.
     *
     * @var string|null
     */
    protected $xComponent;

    /**
     * RepeatableForm instance.
     *
     * @var RepeatableForm
     */
    protected $form;

    /**
     * Preview.
     *
     * @var ColumnBuilder
     */
    protected $preview;

    /**
     * Button text.
     *
     * @var string
     */
    protected $button;

    /**
     * Icon icon.
     *
     * @var string
     */
    protected $icon;

    /**
     * Variant variant.
     *
     * @var string
     */
    protected $variant;

    /**
     * Create new Repeatable instance.
     *
     * @param  Block       $field
     * @param  string|null $type
     * @return void
     */
    public function __construct(Block $field, string $type = null)
    {
        $this->field = $field;

        if (! is_null($type)) {
            $this->type = $type;
        } elseif (is_null($this->type)) {
            throw new InvalidArgumentException(
                'Missing property [type] for '.self::class
            );
        }

        if (is_null($this->button)) {
            $this->button = $this->type;
        }
    }

    /**
     * Prepare preview.
     *
     * @param  ColumnBuilder $builder
     * @return void
     */
    protected function preview(ColumnBuilder $builder): void
    {
    }

    /**
     * Handle form.
     *
     * @param  RepeatableForm $form
     * @return void
     */
    protected function form(RepeatableForm $form): void
    {
        //
    }

    /**
     * Prepare attributes before updating the content of the repeatable.
     *
     * @param  array $attributes
     * @return array
     */
    public function prepareAttributes($attributes)
    {
        return $attributes;
    }

    /**
     * Get repeatable type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Configure repeatable form.
     *
     * @param  Closure|null $closure
     * @return $this
     */
    public function makeForm(Closure $closure = null)
    {
        $form = new RepeatableForm(RepeatableModel::class);

        $form->setRoutePrefix(
            Str::finish("{$this->field->route_prefix}", '/block')
        );

        $form->registered(function ($field) {
            $field->mergeOrSetAttribute('params', [
                'field_id'        => $this->field->id,
                'repeatable_id'   => null,
                'repeatable_type' => $this->type,
                'child_field_id'  => $this->getChildFieldId($field),
            ]);
        });

        $this->preview = new ColumnBuilder;

        if (is_null($closure)) {
            $this->form($form);
            $this->preview($this->preview);
        } else {
            $closure($form, $this->preview);
        }

        $this->form = $form;

        return $this;
    }

    /**
     * Get child field id.
     *
     * @param  Field       $field
     * @return string|void
     */
    protected function getChildfieldId(Field $field)
    {
        if (! $field instanceof LaravelRelationField) {
            return;
        }

        return $field->id;
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
            'button'  => $this->button,
            'icon'    => $this->icon,
            'variant' => $this->variant,
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
     * Get blade component.
     *
     * @return string|null
     */
    public function getX()
    {
        return $this->xComponent;
    }

    /**
     * Set button text.
     *
     * @param  string $text
     * @return $this
     */
    public function button($text)
    {
        $this->button = $text;

        return $this;
    }

    /**
     * Set icon.
     *
     * @param  string $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set variant.
     *
     * @param  string $variant
     * @return $this
     */
    public function variant($variant)
    {
        $this->variant = $variant;

        return $this;
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
        $this->view = $name;
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
        return ! is_null($this->view);
    }

    /**
     * Get view name.
     *
     * @return string|null
     */
    public function getView()
    {
        return view($this->view, $this->viewData);
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
