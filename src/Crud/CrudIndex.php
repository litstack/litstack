<?php

namespace Fjord\Crud;

use Closure;
use Fjord\Config\ConfigHandler;
use Fjord\Page\Page;
use Fjord\Page\Table\ColumnBuilder;
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
            $builder = new ColumnBuilder
        );
        $table->model($this->config->model);
        $table->action(ucfirst(__f('base.delete')), $this->config->controller.'@test');

        $closure($builder);
        $this->component($table->getComponent());

        return $table;
    }
}
