<?php

namespace Fjord\Vue\Crud;

use Fjord\Vue\BaseCol;
use Fjord\Application\Config\ConfigItem;

class RelationTable extends ConfigItem
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
     * @param value $label
     * @return \Fjord\Vue\BaseCol $col
     */
    public function col(string $value)
    {
        $col = (new BaseCol())->value($value);

        $this->cols[] = $col;

        return $col;
    }

    /**
     * Add table component.
     *
     * @param string $name
     * @return \Fjord\Vue\BaseCol
     */
    public function component(string $name)
    {
        return $this->col('')->component($name);
    }

    /**
     * Get cols.
     *
     * @return array $cols
     */
    public function getArray(): array
    {
        return $this->cols;
    }
}
