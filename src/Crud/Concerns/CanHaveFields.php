<?php

namespace Ignite\Crud\Concerns;

use Ignite\Contracts\Crud\Formable;
use Ignite\Crud\BaseForm;
use Illuminate\Support\Str;

trait CanHaveFields
{
    /**
     * Forms.
     *
     * @var array
     */
    protected $forms = [];

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
     * Add form field to table.
     *
     * @param  string $formKey
     * @param  string $title
     * @return void
     */
    public function field($formKey, $label = null)
    {
        $this->canHaveFields($label);

        if (! $label) {
            $formKey = Str::snake($label = $formKey);
        }

        $this->forms[$formKey] = $form = $this->makeForm();

        $form->setRoutePrefix(
            strip_slashes($this->config->routePrefix().'/{id}/api/index')
        );

        $form->registered(function ($field) use ($label, $formKey) {
            $field->noTitle();

            $field->mergeOrSetAttribute('params', ['form_key' => $formKey]);

            $field->rendering(function ($field) use ($formKey) {
                if (! $field instanceof Formable) {
                    return;
                }

                if (! $form = $field->getForm()) {
                    return;
                }

                $form->registered(
                    fn ($nested) => $nested->mergeOrSetAttribute('params', ['form_key' => $formKey])
                );
            });

            $this->component('lit-col-field')
                ->label($label)
                ->prop('colField', $field)
                ->link(false);
        });

        return $form;
    }

    /**
     * This method is used to throw an exception when it the column builder cannot have fields.
     *
     * @param  string $label
     * @return bool
     */
    protected function canHaveFields($label)
    {
    }

    /**
     * Make new form.
     *
     * @return BaseForm
     */
    protected function makeForm(): BaseForm
    {
        return new BaseForm($this->config->model);
    }
}
