<?php

namespace Ignite\Crud;

use Ignite\Config\ConfigHandler;
use Ignite\Contracts\Page\Column;
use Ignite\Contracts\Page\Column as ColumnContract;
use Ignite\Page\Actions\ActionComponent;
use Ignite\Page\Table\ColumnBuilder;
use Ignite\Vue\Component;

class CrudColumnBuilder extends ColumnBuilder
{
    use Traits\ColumnBuilderHasFields;

    /**
     * Crud config handler instance.
     *
     * @var ConfgHandler
     */
    protected $config;

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
     * Add table column to cols stack.
     *
     * @param  string       $label
     * @return Column|mixed
     */
    public function col($label = '')
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

        return '{_lit_route}';
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
     * @param  string          $title
     * @param  string          $action
     * @return Component|mixed
     */
    public function action($title, $action)
    {
        $wrapper = parent::action($title, $action);

        $this->config->bindAction(last($this->columns));

        if (! $wrapper->check()) {
            unset($this->columns[count($this->columns) - 1]);
        }

        return $wrapper;
    }

    /**
     * Create new actions column.
     *
     * @param  string    $title
     * @param  string    $action
     * @return Component
     */
    public function actions($actions, $text = '<i class="fas fa-ellipsis-v"></i>'): ColumnContract
    {
        $column = parent::actions($actions, $text);

        foreach (last($this->columns)->getChildren() as $child) {
            if ($child instanceof ActionComponent) {
                $this->config->bindAction($child);
            }
        }

        return $column;
    }
}
