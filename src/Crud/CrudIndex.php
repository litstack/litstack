<?php

namespace Fjord\Crud;

use Closure;
use Fjord\Config\ConfigHandler;
use Fjord\Crud\Actions\DestroyAction;
use Fjord\Page\Page;
use Fjord\Page\Table\Table;

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
     * @param  \Fjord\Vue\Component $component
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
     * @return \Fjord\Page\Table\Table
     */
    public function table(Closure $closure)
    {
        $this->table = $table = new Table(
            $this->config->routePrefix(),
            $builder = new CrudColumnBuilder($this->config)
        );

        $table->model($this->config->model);
        $table->singularName($this->config->names['singular']);
        $table->pluralName($this->config->names['plural']);

        // TODO:
        $table->action(ucfirst(__f('base.delete')), DestroyAction::class);

        //$table->action(ucfirst(__f('base.delete')), $this->config->controller.'@test');

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
