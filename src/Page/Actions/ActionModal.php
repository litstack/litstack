<?php

namespace Ignite\Page\Actions;

use Closure;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Field;
use Ignite\Support\Bootstrap;
use Ignite\Vue\Component;
use Ignite\Vue\Traits\StaticComponentName;
use InvalidArgumentException;

class ActionModal extends Component
{
    use StaticComponentName;

    /**
     * Component name.
     *
     * @var string
     */
    protected $name = 'b-modal';

    /**
     * Form instance.
     *
     * @var BaseForm|null
     */
    protected $form;

    /**
     * Handle beforeMount.
     *
     * @return void
     */
    public function beforeMount()
    {
        $this->confirmVariant(Bootstrap::PRIMARY);
        $this->confirmText('Run');
        $this->size('md');
        //$this->prop('footer-variant', 'ligjt');
    }

    /**
     * Set modal message.
     *
     * @param  string $message
     * @return void
     */
    public function message($message)
    {
        return $this->prop('message', $message);
    }

    /**
     * Set modal title.
     *
     * @param  string $title
     * @return $this
     */
    public function title($title)
    {
        return $this->prop('title-html', $title);
    }

    /**
     * Set modal size.
     * Possible sizes: sm, md, lg, xl.
     *
     * @param  string $size
     * @return void
     */
    public function size($size)
    {
        if (! in_array($size, ['sm', 'md', 'lg', 'xl'])) {
            throw new InvalidArgumentException("Invalid size [{$size}], valid sizes are: sm, md, lg, xl");
        }

        return $this->prop('size', $size);
    }

    /**
     * Set confirm vairant.
     *
     * @param  string $variant
     * @return $this
     */
    public function confirmVariant(string $variant)
    {
        return $this->prop('ok-variant', $variant);
    }

    /**
     * Set confirm text.
     *
     * @param  string $text
     * @return $this
     */
    public function confirmText($text)
    {
        return $this->prop('ok-title-html', $text);
    }

    /**
     * Remove cancel button.
     *
     * @param  bool  $only
     * @return $this
     */
    public function confirmOnly(bool $only = true)
    {
        return $this->prop('ok-only', $only);
    }

    /**
     * Set cancel button variant.
     *
     * @param  string $variant
     * @return $this
     */
    public function cancelVariant(string $variant)
    {
        return $this->prop('cancel-variant', $variant);
    }

    /**
     * Set cancel button text.
     *
     * @param  string $title
     * @return $this
     */
    public function cancelText($title)
    {
        return $this->prop('cancel-title-html', $title);
    }

    /**
     * Create modal form.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function form(Closure $closure)
    {
        $form = new BaseForm('');

        $form->setRoutePrefix('');

        $form->registering(function (Field $field) {
            $field->setAttribute('storable', false);
            $field->setAttribute('for_action', true);
        });

        $closure($form);

        $this->form = $form;

        return $this;
    }

    /**
     * Get form instance.
     *
     * @return BaseForm|null
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Render ActionModal.
     *
     * @return array
     */
    public function render(): array
    {
        return array_merge(parent::render(), [
            'form' => $this->form,
        ]);
    }
}
