<?php

namespace Ignite\Crud;

use Closure;
use Ignite\Config\ConfigHandler;
use Ignite\Crud\Actions\DestroyAction;
use Ignite\Page\Page;
use Ignite\Page\Table\Table;

class CrudIndex extends Page
{
    /**
     * ConfigHandler instance.
     *
     * @var ConfigHandler
     */
    protected $config;

    /**
     * Crud index table.
     *
     * @var CrudIndexTable
     */
    protected $table;

    /**
     * Create new CrudIndex instance.
     *
     * @param ConfigHandler $config
     */
    public function __construct(ConfigHandler $config)
    {
        parent::__construct();

        $this->config = $config;
        $this->setDefaults();
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
                ['model' => $this->config->model]
            ));
    }

    /**
     * Set defaults.
     *
     * @return void
     */
    public function setDefaults()
    {
        $this->expand(false);
    }

    /**
     * Get CrudIndex table.
     *
     * @return void
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Create CrudIndex table.
     *
     * @param  Closure                 $closure
     * @return \Ignite\Page\Table\Table
     */
    public function table(Closure $closure)
    {
        $this->table = $table = new CrudIndexTable(
            $this->config->routePrefix(),
            $builder = new CrudColumnBuilder($this->config)
        );

        $table->model($this->config->model);
        $table->singularName($this->config->names['singular']);
        $table->pluralName($this->config->names['plural']);

        $table->action(ucfirst(__lit('base.delete')), DestroyAction::class);

        $closure($builder);
        $this->component($table->getComponent());

        return $table;
    }

    /**
     * Render CrudIndex.
     *
     * @return array
     */
    public function render(): array
    {
        foreach ($this->table->getActions() as $component) {
            $component->on('click', RunCrudActionEvent::class)
                ->prop('eventData', array_merge(
                    $component->getProp('eventData'),
                    ['model' => $this->config->model]
                ));
        }

        return parent::render();
    }
}
