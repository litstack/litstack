<?php

namespace Fjord\Vue;

class Col extends BaseCol
{
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
}
