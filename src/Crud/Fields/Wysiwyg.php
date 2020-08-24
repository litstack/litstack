<?php

namespace Lit\Crud\Fields;

use Lit\Crud\BaseField;
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
    public function mount()
    {
        //
    }

    /**
     * Set font colors.
     *
     * @param  array $colors
     * @return $this
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
     * @param  string $path
     * @return $this
     */
    public function css(string $path)
    {
        $this->setAttribute('css', File::get($path));

        return $this;
    }

    /**
     * Determines if the wysiwyg table has a header.
     *
     * @param  bool  $hasHeader
     * @return $this
     */
    public function tableHasHeader(bool $hasHeader = true)
    {
        $this->setAttribute('tableHasHeader', $hasHeader);

        return $this;
    }

    /**
     * Cast field value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function castValue($value)
    {
        return (string) $value;
    }
}
