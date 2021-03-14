<?php

namespace Ignite\Contracts\Page;

interface Column
{
    /**
     * Set column label.
     *
     * @param  string $label
     * @return $this
     */
    public function label($label);

    /**
     * Set the column value.
     *
     * @param  string $value
     * @return $this
     */
    public function value($value);

    /**
     * Set text align to right or left.
     *
     * @param  bool  $right
     * @return $this
     */
    public function right(bool $right = true);

    /**
     * Set text align to center.
     *
     * @param  bool  $center
     * @return $this
     */
    public function center(bool $center = true);

    /**
     * Reduce column to minimum width.
     *
     * @param  bool  $small
     * @return $this
     */
    public function small(bool $small = true);

    /**
     * Set column link.
     *
     * @param  string|bool $link
     * @return $this
     */
    public function link($link);

    /**
     * Set attribute by which the column should be sorted.
     *
     * @param  string $attribute
     * @return $this
     */
    public function sortBy($attribute);

    /**
     * Set regular expression and replace for column value.
     *
     * @param  string $regex
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function regex($regex, $replace);

    /**
     * Strip html from column value.
     *
     * @param  bool  $strip
     * @return $this
     */
    public function stripHtml(bool $strip = true);

    /**
     * Set a maxium of characters that should be displayed in the column.
     *
     * @param  int         $max
     * @return $this|mixed
     */
    public function maxChars(int $max);

    /**
     * Table column style.
     *
     * @param  string $style
     * @return $this
     */
    public function style($style);
}
