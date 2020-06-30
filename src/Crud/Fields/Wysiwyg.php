<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\BaseField;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class Wysiwyg extends BaseField
{
    use Traits\FieldHasRules;
    use Traits\TranslatableField;

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

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        //
    }

    /**
     * Set font colors.
     *
     * @param array $colors
     *
     * @return void
     */
    public function colors($colors, ...$more)
    {
        $colors = array_merge(Arr::wrap($colors), $more);

        $this->setAttribute('colors', $colors);

        return $this;
    }

    /**
     * Add custom css to editor.
     *
     * @param string $path
     *
     * @return void
     */
    public function css(string $path)
    {
        $this->setAttribute('css', File::get($path));

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
