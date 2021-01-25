<?php

namespace Ignite\Crud\Fields;

use Ignite\Crud\BaseField;
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
    protected $component = 'lit-field-wysiwyg';

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
        $this->setAttribute('enableInputRules', false);
        $this->setAttribute('enablePasteRules', false);
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
     * Set fields that should be shown.
     *
     * @param  array $fields
     * @return $this
     */
    public function only($fields)
    {
        $this->setAttribute('only', $fields);

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
     * Enable input rules.
     *
     * @param  bool $search
     * @return self
     */
    public function enableInputRules(bool $enabled = true)
    {
        $this->setAttribute('enableInputRules', $enabled);

        return $this;
    }

    /**
     * Enable paste rules.
     *
     * @param  bool $search
     * @return self
     */
    public function enablePasteRules(bool $enabled = true)
    {
        $this->setAttribute('enablePasteRules', $enabled);

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
