<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\Concerns\CanHaveFields;
use Ignite\Page\Table\ColumnBuilder;
use InvalidArgumentException;

class RelationColumnBuilder extends ColumnBuilder
{
    use CanHaveFields;

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
    public function __construct(ConfigHandler $config = null)
    {
        $this->config = $config;
    }

    /**
     * This method is used to throw an exception when it the column builder cannot have fields.
     *
     * @param  string $label
     * @return void
     */
    protected function canHaveFields($label)
    {
        if ($this->config) {
            return;
        }

        throw new InvalidArgumentException(
            'Fields can only be added to a relation preview table when the related crud config has been set.'
        );
    }
}
