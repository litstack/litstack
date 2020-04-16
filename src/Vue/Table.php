<?php

namespace Fjord\Vue;

use Fjord\Application\Config\ConfigItem;

class Table extends ConfigItem
{
    /**
     * Column stack.
     *
     * @var array
     */
    protected $cols = [];

    /**
     * Add table column to cols stack.
     *
     * @param string $label
     * @return \Fjord\Vue\Col $col
     */
    public function col(string $label = '')
    {
        $col = new Col($label);

        $this->cols[] = $col;

        return $col;
    }

    /**
     * Add table column to cols stack and set component.
     *
     * @param string $component
     * @return \Fjord\Vue\Col $col
     */
    public function component(string $component)
    {
        return $this->col()->component($component);
    }

    /**
     * Get cols.
     *
     * @return array $cols
     */
    public function toArray()
    {
        return $this->cols;
    }
}
