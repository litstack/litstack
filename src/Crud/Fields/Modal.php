<?php

namespace Fjord\Crud\Fields;

use Closure;
use Fjord\Crud\Field;
use Fjord\Crud\BaseForm;
use Illuminate\Support\Facades\Hash;
use Fjord\Crud\Fields\Concerns\FieldHasForm;

class Modal extends Field
{
    use FieldHasForm;

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
        'name',
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
        'name',
        'form',
        'size',
        'variant',
        'confirmWithPassword'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'size' => 'md',
        'variant' => 'secondary',
        'confirmWithPassword' => false
    ];

    /**
     * Confirm with password.
     *
     * @param boolean $confirm
     * @return void
     */
    public function confirmWithPassword($confirm = true)
    {
        $this->setAttribute('confirmWithPassword', $confirm);

        return $this;
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
            "$this->route_prefix/modal/{modal_id}"
        );

        $closure($form);

        if ($this->confirmWithPassword) {
            $form->password('__p_confirm')
                ->title('Password')
                ->confirm();
        }

        $this->setAttribute('form', $form);

        return $this;
    }
}
