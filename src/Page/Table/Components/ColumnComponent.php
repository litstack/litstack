<?php

namespace Fjord\Page\Table\Components;

use ErrorException;
use Fjord\Contracts\Page\Column;
use Fjord\Exceptions\Traceable\InvalidArgumentException;
use Fjord\Vue\Component;

class ColumnComponent extends Component implements Column
{
    /**
     * Set label.
     *
     * @param  string $label
     * @return $this
     */
    public function label($label)
    {
        return $this->prop('label', $label);
    }

    /**
     * Set value.
     *
     * @param  string $value
     * @return $this
     */
    public function value($value)
    {
        return $this->prop('value', $value);
    }

    /**
     * Reduce column to minimum width.
     *
     * @param  bool  $small
     * @return $this
     */
    public function small(bool $small = true)
    {
        return $this->prop('small', $small);
    }

    /**
     * Set column link.
     *
     * @param  string|bool $link
     * @return $this
     */
    public function link($link)
    {
        return $this->prop('link', $link);
    }

    /**
     * Set attribute by which the column should be sorted.
     *
     * @param  string $attribute
     * @return $this
     */
    public function sortBy($attribute)
    {
        return $this->prop('sort_by', $attribute);
    }

    /**
     * Set regular expression and replace for column value.
     *
     * @param  string $regex
     * @param  string $replace
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function regex($regex, $replace)
    {
        $this->prop('regex', $regex);
        $this->prop('regex_replace', $replace);

        // Test regular expression:
        try {
            preg_match($regex, '');
        } catch (ErrorException $e) {
            throw new InvalidArgumentException($e->getMessage(), [
                'function' => 'regex',
                'class'    => self::class,
            ]);
        }

        return $this;
    }

    /**
     * Strip html from column value.
     *
     * @param  bool  $strip
     * @return $this
     */
    public function stripHtml(bool $strip = true)
    {
        return $this->prop('strip_html', $strip);
    }

    /**
     * Set a maxium of characters that should be displayed in the column.
     *
     * @param  int   $max
     * @return $this
     */
    public function maxChars(int $max)
    {
        return $this->prop('max_chars', $max);
    }
}
