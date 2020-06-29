<?php

namespace Fjord\Crud\Repositories;

use Fjord\Crud\Field;
use Fjord\Config\ConfigHandler;

abstract class BaseFieldRepository
{
    /**
     * Field instance.
     *
     * @var \Fjord\Crud\Field
     */
    protected $field;

    /**
     * Create new BaseFieldRepository instance.
     */
    public function __construct(ConfigHandler $config, $controller, Field $field = null)
    {
        $this->config = $config;
        $this->controller = $controller;
        $this->field = $field;
    }
}
