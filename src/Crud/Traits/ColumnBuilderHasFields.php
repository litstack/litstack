<?php

namespace Ignite\Crud\Traits;

use Closure;
use Ignite\Contracts\Crud\Formable;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Field;
use Illuminate\Support\Str;

trait ColumnBuilderHasFields
{
    /**
     * Forms.
     *
     * @var array
     */
    protected $forms = [];

    /**
     * Add form field to table.
     *
     * @param  string  $title
     * @param  Closure $closure
     * @return void
     */
    public function field($label, Closure $closure = null)
    {
        $formKey = Str::snake($label);

        $this->forms[$formKey] = $form = new BaseForm($this->getFieldModel());

        $form->setRoutePrefix(
            strip_slashes($this->getFieldRoutePrefix())
        );

        $form->registered(function ($field) use ($label, $formKey, $closure) {
            $field->noTitle();

            $field->mergeOrSetAttribute('params', $this->getFieldParams($formKey, $field));

            $field->rendering(function ($field) use ($formKey) {
                if (! $field instanceof Formable) {
                    return;
                }

                if (! $form = $field->getForm()) {
                    return;
                }

                $form->registered(
                    fn ($nested) => $nested->mergeOrSetAttribute('params', $this->getFieldParams($formKey))
                );
            });

            $column = $this->component($this->getFieldColumnComponentName())
                ->label($label)
                ->prop('col-field', $field)
                ->link(false);

            if ($closure instanceof Closure) {
                $closure($column);
            }
        });

        return $form;
    }

    /**
     * Get form for the given key.
     *
     * @param  string              $key
     * @return BaseForm|mixed|void
     */
    public function getForm($key)
    {
        return $this->forms[$key] ?? null;
    }

    /**
     * Get field params.
     *
     * @param  string $formKey
     * @param  Field  $field
     * @return array
     */
    protected function getFieldParams($formKey, Field $field): array
    {
        return ['form_key' => $formKey];
    }

    /**
     * Get the model class belonging to the field.
     *
     * @return string
     */
    protected function getFieldModel()
    {
        return $this->config->model;
    }

    /**
     * Get field route prefix.
     *
     * @return string
     */
    protected function getFieldRoutePrefix()
    {
        return $this->config->routePrefix().'/{id}/api/index';
    }

    /**
     * Get field column component name.
     *
     * @return string
     */
    protected function getFieldColumnComponentName()
    {
        if (property_exists($this, 'fieldColumnComponentName')) {
            return $this->fieldColumnComponentName;
        }

        return 'lit-col-field';
    }
}
