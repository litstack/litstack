<?php

namespace Ignite\Page\Table;

use ErrorException;
use Ignite\Contracts\Page\Column as ColumnInterface;
use Ignite\Contracts\Page\Table;
use Ignite\Exceptions\MissingAttributeException;
use Ignite\Exceptions\Traceable\InvalidArgumentException;
use Ignite\Support\HasAttributes;
use Ignite\Support\VueProp;

class Column extends VueProp implements ColumnInterface
{
    use HasAttributes;

    /**
     * Column classes.
     *
     * @var array
     */
    protected $classes = [];

    /**
     * Create new Col instance.
     *
     * @param  string $label
     * @return void
     */
    public function __construct($label = null)
    {
        if ($label !== null) {
            $this->label($label);
            $this->value($label);
        }
    }

    /**
     * Set parent table.
     *
     * @param  Table $table
     * @return void
     */
    public function setTable(Table $table)
    {
        $this->table = $table;
    }

    /**
     * Set label.
     *
     * @param  string $label
     * @return $this
     */
    public function label($label)
    {
        $this->setAttribute('label', $label);

        return $this;
    }

    /**
     * Set white-space to `nowrap`.
     *
     * @return $this
     */
    public function nowrap()
    {
        return $this->style('white-space: nowrap');
    }

    /**
     * Set value.
     *
     * @param  string     $value
     * @param  array|null $options
     * @param  mixed      $default
     * @return $this
     */
    public function value($value, array $options = null, $default = null)
    {
        $this->setAttribute('value', $value);
        $this->setAttribute('value_options', $options);
        $this->setAttribute('default_value', $default);

        return $this;
    }

    /**
     * Set table column style.
     *
     * @param  string     $style
     * @param  array|null $options
     * @param  mixed      $default
     * @return $this
     */
    public function style($style, array $options = null, $default = null)
    {
        $this->setAttribute('style', $style);
        $this->setAttribute('style_options', $options);
        $this->setAttribute('style_value', $default);

        return $this;
    }

    /**
     * Set column class.
     *
     * @param  string $class
     * @return $this
     */
    public function class($class)
    {
        if (! in_array($class, $this->classes)) {
            $this->classes[$class] = $class;
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
        $this->setAttribute('small', $small);

        $this->center();

        return $this;
    }

    /**
     * Set text align to right or left.
     *
     * @param  bool  $right
     * @return $this
     */
    public function right(bool $right = true)
    {
        $this->setAttribute('text_right', $right);

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
        $this->setAttribute('text_center', $center);

        if ($center) {
            $this->right(false);
        }

        return $this;
    }

    /**
     * Set column link.
     *
     * @param  string|bool $link
     * @return $this
     */
    public function link($link)
    {
        $this->setAttribute('link', $link);

        return $this;
    }

    /**
     * Set attribute by which the column should be sorted.
     *
     * @param  string $attribute
     * @return $this
     */
    public function sortBy($attribute)
    {
        $this->setAttribute('sort_by', $attribute);

        return $this;
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
        $this->setAttribute('regex', $regex);
        $this->setAttribute('regex_replace', $replace);

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
        $this->setAttribute('strip_html', $strip);

        return $this;
    }

    /**
     * Set a maxium of characters that should be displayed in the column.
     *
     * @param  int   $max
     * @return $this
     */
    public function maxChars(int $max)
    {
        $this->attributes['max_chars'] = $max;

        return $this;
    }

    /**
     * Check if all required props have been set.
     *
     * @return void
     *
     * @throws MissingAttributeException
     */
    public function checkComplete()
    {
        if (array_key_exists('label', $this->attributes)) {
            return;
        }

        throw new MissingAttributeException(sprintf(
            'Missing required attributes [%s] for table column.',
            implode(', ', ['label']),
        ));
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function render(): array
    {
        $this->checkComplete();

        return array_merge(
            $this->attributes,
            ['classes' => $this->classes]
        );
    }
}
