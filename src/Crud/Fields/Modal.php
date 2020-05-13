<?php

namespace Fjord\Crud\Fields;

use Closure;
use Fjord\Crud\Field;
use Fjord\Crud\BaseForm;

class Modal extends Field
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-modal';

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
        'button',
        'form'
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'hint',
        'button',
        'form',
        'size'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'size' => 'md'
    ];

    /**
     * Create new info instance.
     *
     * @param string $id
     * @param string $model
     * @param string|null $routePrefix
     */
    public function __construct(string $id, string $model, $routePrefix)
    {
        parent::__construct($id, $model, $routePrefix);

        $this->attributes['button'] = $id;
    }

    /**
     * Is field component.
     *
     * @return boolean
     */
    public function isComponent()
    {
        return true;
    }

    public function form(Closure $closure)
    {
        $form = new BaseForm($this->model);

        $form->setRoutePrefix(
            $this->route_prefix
        );

        $closure($form);

        $this->setAttribute('form', $form);

        return $this;
    }
}
