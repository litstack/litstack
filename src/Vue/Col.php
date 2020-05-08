<?php

namespace Fjord\Vue;

use Exception;
use Fjord\Support\VueProp;

class Col extends VueProp
{
    /**
     * Attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create new Col instance.
     *
     * @param string $label
     */
    public function __construct(string $label = null)
    {
        if ($label) {
            $this->label($label);
        }
    }

    /**
     * Set link.
     *
     * @param string|boolean $link
     * @return self
     */
    public function link($link)
    {
        $this->attributes['link'] = $link;

        return $this;
    }

    /**
     * Small column.
     *
     * @return self
     */
    public function small()
    {
        $this->attributes['small'] = true;

        return $this;
    }

    /**
     * Set sort_by.
     *
     * @param string $key
     * @return self
     */
    public function sortBy(string $key)
    {
        $this->attributes['sort_by'] = $key;

        return $this;
    }

    /**
     * Set label.
     *
     * @param string $label
     * @return self
     */
    public function label(string $label)
    {
        $this->attributes['label'] = $label;

        return $this;
    }

    /**
     * Regular expression for column value.
     *
     * @param string $regex
     * @return void
     */
    public function regex($regex, string $replace = '')
    {
        $this->attributes['regex'] = $regex;
        $this->attributes['regex_replace'] = $replace;

        return $this;
    }

    /**
     * Max characters for field
     *
     * @param boolean $strip
     * @return void
     */
    public function stripHtml(bool $strip = true)
    {
        $this->attributes['strip_html'] = $strip;

        return $this;
    }

    /**
     * Max characters for field
     *
     * @param int $max
     * @return void
     */
    public function maxChars(int $max = 100)
    {
        $this->attributes['max_chars'] = $max;

        return $this;
    }

    /**
     * Get attributes
     *
     * @return array
     */
    public function getArray(): array
    {
        $this->checkComplete();

        return $this->attributes;
    }

    /**
     * Set value.
     *
     * @param string $value
     * @return self
     */
    public function value(string $value)
    {
        $this->attributes['value'] = $value;

        return $this;
    }

    /**
     * Check if all required props have been set.
     *
     * @return boolean
     * 
     * @throws \Exception
     */
    public function checkComplete()
    {
        if (array_key_exists('label', $this->attributes)) {
            return true;
        }

        throw new Exception(sprintf(
            'Missing required attributes: [%s] for table column.',
            implode(', ', ['label']),
        ));
    }
}
