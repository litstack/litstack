<?php

namespace Ignite\Crud;

use Closure;
use Ignite\Config\ConfigHandler;
use Ignite\Crud\Actions\DestroyAction;
use Ignite\Page\Actions\ActionComponent;
use Ignite\Page\Page;
use Ignite\Page\Table\Table;
use Illuminate\Http\Request;

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
     * Get the current form.
     *
     * @param  Request             $request
     * @return BaseForm|mixed|null
     */
    public function getForm(Request $request)
    {
        return $this->table->getBuilder()->getForm($request->form_key);
    }

    /**
     * Bind the action to the CrudIndex page.
     *
     * @param  ActionComponent $component
     * @return void
     */
    public function bindAction(ActionComponent $component)
    {
        $this->config->bindAction($component);
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
     * @return CrudIndexTable|null
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Create CrudIndex table.
     *
     * @param  Closure        $closure
     * @return CrudIndexTable
     */
    public function table(Closure $closure)
    {
        $this->table = $table = new CrudIndexTable(
            $this->config,
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
     * Create a new Info Card.
     *
     * @param  string $title
     * @return void
     */
    public function info(string $title = '')
    {
        $info = $this->component('lit-info')->title($title);

        return $info;
    }
}
