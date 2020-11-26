<?php

namespace Ignite\Crud;

use Ignite\Config\ConfigHandler;
use Ignite\Contracts\Crud\Formable;
use Ignite\Contracts\Page\Column;
use Ignite\Contracts\Page\Column as ColumnContract;
use Ignite\Page\Table\ColumnBuilder;
use Ignite\Vue\Component;
use Illuminate\Support\Str;

class CrudColumnBuilder extends ColumnBuilder
{
    /**
     * Crud config handler instance.
     *
     * @var ConfgHandler
     */
    protected $config;

    /**
     * Forms.
     *
     * @var array
     */
    protected $forms = [];

    /**
     * Create new CrudColumnBuilder instance.
     *
     * @param ConfigHandler $config
     */
    public function __construct(ConfigHandler $config)
    {
        $this->config = $config;
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
     * Add form field to table.
     *
     * @param  string $title
     * @return void
     */
    public function field($label)
    {
        $formKey = Str::snake($label);

        $this->forms[$formKey] = $form = new BaseForm($this->config->model);

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
                ->prop('field', $field)
                ->link(false);
        });

        return $form;
    }

    /**
     * Add table column to cols stack.
     *
     * @param  string                        $label
     * @return \Ignite\Contracts\Page\Column
     */
    public function col($label = ''): Column
    {
        return parent::col($label)->link($this->defaultLink());
    }

    /**
     * Add table column to cols stack and set component.
     *
     * @param  string $component
     * @return mixed
     */
    public function component($component): Column
    {
        return parent::component($component)->link($this->defaultLink());
    }

    /**
     * Disable link on all table columns.
     *
     * @return $this
     */
    public function disableLinks()
    {
        foreach ($this->columns as $col) {
            $col->link(false);
        }

        return $this;
    }

    /**
     * Get default link.
     *
     * @return string|bool
     */
    public function defaultLink()
    {
        if (! $this->config->has('show')) {
            return false;
        }

        return $this->config->routePrefix().'/{id}';
    }

    /**
     * Get crud config handler instance.
     *
     * @return ConfigHandler
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Create new action column.
     *
     * @param  string    $title
     * @param  string    $action
     * @return Component
     */
    public function action($title, $action): ColumnContract
    {
        $wrapper = parent::action($title, $action);

        last($this->columns)->on('run', RunCrudActionEvent::class);

        return $wrapper;
    }
}
