<?php

namespace Lit\Page\Table\Components;

use ErrorException;
use Lit\Contracts\Page\Column;
use Lit\Exceptions\Traceable\InvalidArgumentException;
use Lit\Vue\Component;

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
     * Set text align to right or left.
     *
     * @param  bool  $right
     * @return $this
     */
    public function right(bool $right = true)
    {
        $this->prop('text_right', $right);

        if ($right) {
            $this->center(false);
        }

        return $this;
    }

    /**
     * Set text align to center.
     *
     * @param  bool  $center
     * @return $this
     */
    public function center(bool $center = true)
    {
        $this->prop('text_center', $center);

        if ($center) {
            $this->right(false);
        }

        return $this;
    }

    /**
     * Reduce column to minimum width.
     *
     * @param  bool  $small
     * @return $this
     */
    public function small(bool $small = true)
    {
        $this->center();

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

    /**
     * Render ColumnComponent.
     *
     * @return array
     */
    public function render(): array
    {
        return array_merge(
            $this->props,
            parent::render(),
        );
    }
}
