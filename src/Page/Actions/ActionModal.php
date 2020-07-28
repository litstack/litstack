<?php

namespace Fjord\Page\Actions;

use Closure;
use Fjord\Crud\BaseForm;
use Fjord\Crud\Field;
use Fjord\Vue\Component;
use Fjord\Vue\Traits\StaticComponentName;

class ActionModal extends Component
{
    use StaticComponentName;

    protected $name = 'b-modal';

    /**
     * Form instance.
     *
     * @var BaseForm|null
     */
    protected $form;

    public function beforeMount()
    {
        $this->confirmVariant('primary');
        $this->confirmText('Run');
        $this->size('md');
        //$this->prop('footer-variant', 'ligjt');
    }

    public function message($message)
    {
        $this->prop('message', $message);

        return $this;
    }

    public function title($title)
    {
        $this->prop('title-html', $title);

        return $this;
    }

    public function size($size)
    {
        $this->prop('size', $size);

        return $this;
    }

    public function confirmVariant(string $variant)
    {
        $this->prop('ok-variant', $variant);

        return $this;
    }

    public function confirmText($text)
    {
        $this->prop('ok-title-html', $text);

        return $this;
    }

    public function confirmOnly($only = true)
    {
        $this->prop('ok-only', $only);

        return $this;
    }

    public function cancelVariant(string $variant)
    {
        $this->prop('cancel-variant', $variant);

        return $this;
    }

    public function cancelText($title)
    {
        $this->prop('cancel-title-html', $title);

        return $this;
    }

    public function form(Closure $closure)
    {
        $form = new BaseForm('');

        $form->setRoutePrefix('');

        $form->registering(function (Field $field) {
            $field->setAttribute('storable', false);
        });

        $closure($form);

        $this->form = $form;

        return $this;
    }

    public function render(): array
    {
        return array_merge(parent::render(), [
            'form' => $this->form,
        ]);
    }
}
