<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Code extends Field
{
    use Concerns\FieldHasRules,
        Concerns\FormItemWrapper;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-code';

    /**
     * Required attributes.
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
        $this->tabSize(4);
        $this->theme('default');
        $this->lineNumbers(true);
        $this->line(true);
        $this->language('text/html');
        $this->options([]);
    }

    /**
     * Set options.
     *
     * @param array $options
     * @return $this
     */
    public function options(array $options)
    {
        $this->setAttribute('options', $options);

        return $this;
    }

    /**
     * Set language.
     *
     * @param boolean $line
     * @return $this
     */
    public function language(string $language)
    {
        $this->setAttribute('language', $language);

        return $this;
    }

    /**
     * Set line.
     *
     * @param boolean $line
     * @return $this
     */
    public function line(bool $line = true)
    {
        $this->setAttribute('line', $line);

        return $this;
    }

    /**
     * Set lineNumbers.
     *
     * @param integer $lineNumbers
     * @return $this
     */
    public function lineNumbers(int $lineNumbers)
    {
        $this->setAttribute('lineNumbers', $lineNumbers);

        return $this;
    }

    /**
     * Set theme.
     *
     * @param string $theme
     * @return $this
     */
    public function theme(string $theme)
    {
        $this->setAttribute('theme', $theme);

        return $this;
    }

    /**
     * Set tabSize.
     *
     * @param integer $tabSize
     * @return $this
     */
    public function tabSize(int $tabSize)
    {
        $this->setAttribute('tabSize', $tabSize);

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
