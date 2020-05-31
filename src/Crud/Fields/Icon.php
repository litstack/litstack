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
     * Set default icons attribute.
     *
     * @return array
     */
    public function setIconsDefault()
    {
        return require fjord_path('src/Crud/Fields/Defaults/fontawesome_icons.php');
    }

    /**
     * Set default icons attribute.
     *
     * @return array
     */
    public function setSearchDefault()
    {
        return true;
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
