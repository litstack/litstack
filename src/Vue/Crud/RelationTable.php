<?php

namespace Fjord\Vue\Crud;

use Fjord\Vue\Col;
use Fjord\Support\VueProp;

class RelationTable extends VueProp
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
        $col = (new Col($value))->value($value);

        $this->cols[] = $col;

        return $col;
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
