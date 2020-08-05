<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\BaseField;

class Icon extends BaseField
{
    use Traits\FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-icon';

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
        $this->icons(require fjord_path('src/Crud/Fields/Defaults/fontawesome_icons.php'));
        $this->search(true);
    }

    /**
     * Set search.
     *
     * @param bool $search
     *
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
     * @param array $icons
     *
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
     * @param mixed $value
     *
     * @return bool
     */
    public function cast($value)
    {
        return (string) $value;
    }
}
