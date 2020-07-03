<?php

namespace Fjord\Crud;

use Closure;
use Fjord\Config\ConfigHandler;
use Fjord\Vue\Page\Page;
use Fjord\Vue\Page\Traits\Expandable;
use Fjord\Vue\Page\Traits\HasCharts;

class CrudIndex extends Page
{
    use HasCharts, Expandable;

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
     * @param Closure $closure
     *
     * @return $this
     */
    public function table(Closure $closure)
    {
        $table = new CrudIndexTable($this->config);

        $this->table = $table;

        $closure($table->getTable());

        $this->component($table->getComponent())
            ->prop('table', $table);

        return $table;
    }
}
