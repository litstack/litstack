<?php

namespace Ignite\Crud\Fields;

use Ignite\Crud\BaseField;

class Icon extends BaseField
{
    use Traits\FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-icon';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Set default attributes.
     *
     * @return void
     */
    public function mount()
    {
        $this->icons(require lit_vendor_path('src/Crud/Fields/Defaults/fontawesome_icons.php'));
        $this->search(true);
    }

    /**
     * Set search.
     *
     * @param  bool $search
     * @return self
     */
    public function search(bool $search = true)
    {
        $this->setAttribute('search', $search);

        return $this;
    }

    /**
     * Set icons.
     *
     * @param  array $icons
     * @return self
     */
    public function icons(array $icons)
    {
        $this->setAttribute('icons', $icons);

        return $this;
    }

    /**
     * Cast field value.
     *
     * @param  mixed $value
     * @return bool
     */
    public function castValue($value)
    {
        return (string) $value;
    }
}
