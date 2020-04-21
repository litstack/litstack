<?php

namespace Fjord\Vue\Concerns;

trait IsTableCol
{
    /**
     * Attributes.
     *
     * @var array
     */
    protected $attributes = [];

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
        $this->attributes['reduce'] = true;

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
     * Require vue props.
     *
     * @return array
     */
    public function required()
    {
        return ['label'];
    }
}
