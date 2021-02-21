<?php

namespace Ignite\Crud;

use Ignite\Config\ConfigHandler;
use Ignite\Contracts\Page\Action;
use Ignite\Crud\Filter\FilterForm;
use Ignite\Page\Table\Table;

class CrudIndexTable extends Table
{
    /**
     * Crud config.
     *
     * @var ConfigHandler
     */
    protected $config;

    /**
     * Table column builder.
     *
     * @var CrudColumnBuilder
     */
    protected $builder;

    /**
     * Create new CrudIndexTable instance.
     *
     * @param  ConfigHandler     $config
     * @param  CrudColumnBuilder $builder
     * @return void
     */
    public function __construct(ConfigHandler $config, CrudColumnBuilder $builder)
    {
        $this->config = $config;

        parent::__construct($config->routePrefix(), $builder);
    }

    /**
     * Add action.
     *
     * @param  string $title
     * @param  string $action
     * @return $this
     */
    public function action($title, $action)
    {
        parent::action($title, $action);

        $this->config->bindAction(last($this->actions));

        return $this;
    }

    /**
     * Set table filters.
     *
     * @param  array $filter
     * @return $this
     */
    public function filter(array $filter)
    {
        foreach ($filter as $title => $scopes) {
            if (is_array($scopes)) {
                continue;
            }

            $filter[$title] = new FilterForm($scopes);
        }

        $this->setAttribute('filter', $filter);

        return $this;
    }
}
