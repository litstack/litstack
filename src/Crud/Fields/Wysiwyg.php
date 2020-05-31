<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Wysiwyg extends Field
{
    use Concerns\FieldHasRules,
        Concerns\TranslatableField,
        Concerns\FormItemWrapper,
        Concerns\FieldHasPlaceholder;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-wysiwyg';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    public function setDefaultAttributes()
    {
        $this->toolbar([
            'heading',
            '|',
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            'blockQuote',
        ]);
    }

    /**
     * Set toolbarFormat.
     *
     * @param //TODO: [type] $format
     * @return $this
     */
    public function toolbarFormat($toolbarFormat)
    {
        $this->setAttribute('toolbarFormat', $toolbarFormat);

        return $this;
    }

    /**
     * Set formats
     *
     * @param //TODO: [type] $format
     * @return $this
     */
    public function formats($format)
    {
        $this->setAttribute('formats', $formats);

        return $this;
    }

    /**
     * Set toolbar.
     *
     * @param array $toolbar
     * @return $this
     */
    public function toolbar(array $toolbar)
    {
        $this->setAttribute('toolbar', $toolbar);

        return $this;
    }

    /**
     * Cast field value.
     *
     * @param mixed $value
     * @return boolean
     */
    public function cast($value)
    {
        return (string) $value;
    }
}
