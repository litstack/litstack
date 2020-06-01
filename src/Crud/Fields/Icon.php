<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Icon extends Field
{
    use Concerns\FormItemWrapper;

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
    public function setDefaultAttributes()
    {
        $this->icons(require fjord_path('src/Crud/Fields/Defaults/fontawesome_icons.php'));
        $this->search(true);
    }

    /**
     * Set search.
     *
     * @param boolean $search
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
     * @return boolean
     */
    public function cast($value)
    {
        return (string) $value;
    }
}
